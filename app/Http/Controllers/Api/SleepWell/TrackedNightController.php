<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\Listener;
use App\Models\SleepWell\TrackedNight;
use App\Services\SleepWell\NightInsightsBuilder;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrackedNightController extends Controller
{
    public function __construct(
        private readonly NightInsightsBuilder $builder,
    ) {
    }

    public function start(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'device_id' => ['required', 'string', 'max:120'],
            'session_id' => ['nullable', 'integer', 'exists:sleepwell_sleep_sessions,id'],
            'preferred_track_id' => ['nullable', 'integer', 'exists:sleepwell_audio_tracks,id'],
            'entry_point' => ['nullable', 'string', 'max:60'],
            'started_at' => ['nullable', 'date'],
            'tracked_date' => ['nullable', 'date'],
            'sleep_goal_minutes' => ['nullable', 'integer', 'min:360', 'max:600'],
            'smart_alarm_window_minutes' => ['nullable', 'integer', 'min:0', 'max:60'],
            'wake_alarm_time' => ['nullable', 'string', 'max:20'],
            'mix_snapshot' => ['nullable', 'array'],
            'metadata' => ['nullable', 'array'],
        ]);

        $listener = $this->resolveListener($payload['device_id']);
        $startedAt = isset($payload['started_at'])
            ? CarbonImmutable::parse($payload['started_at'])
            : now()->toImmutable();

        $night = TrackedNight::query()->create([
            'listener_id' => $listener->id,
            'session_id' => $payload['session_id'] ?? null,
            'preferred_track_id' => $payload['preferred_track_id'] ?? null,
            'started_at' => $startedAt,
            'tracked_date' => $payload['tracked_date'] ?? $startedAt->toDateString(),
            'entry_point' => $payload['entry_point'] ?? null,
            'status' => 'active',
            'sleep_goal_minutes' => $payload['sleep_goal_minutes'] ?? 480,
            'smart_alarm_window_minutes' => $payload['smart_alarm_window_minutes'] ?? 30,
            'wake_alarm_time' => $payload['wake_alarm_time'] ?? null,
            'mix_snapshot' => $payload['mix_snapshot'] ?? [],
            'metadata' => $payload['metadata'] ?? [],
        ]);

        return response()->json([
            'night' => $this->serializeActiveNight($night),
        ], 201);
    }

    public function uploadRecording(Request $request, TrackedNight $trackedNight): JsonResponse
    {
        $payload = $request->validate([
            'device_id' => ['nullable', 'string', 'max:120'],
            'duration_seconds' => ['nullable', 'integer', 'min:1'],
            'recording' => ['required', 'file', 'mimetypes:audio/mp4,audio/m4a,audio/aac,audio/mpeg,audio/wav,audio/webm,video/mp4'],
        ]);

        $this->authorizeNightAccess($request, $trackedNight, $payload['device_id'] ?? null);

        $extension = $request->file('recording')->getClientOriginalExtension() ?: 'm4a';
        $path = $request->file('recording')->storeAs(
            "sleepwell/tracked-nights/{$trackedNight->listener_id}/{$trackedNight->id}",
            Str::uuid().".{$extension}",
            'public',
        );

        $trackedNight->update([
            'recording_path' => $path,
            'recording_duration_seconds' => $payload['duration_seconds'] ?? $trackedNight->recording_duration_seconds,
            'recording_uploaded_at' => now(),
            'status' => 'uploaded',
        ]);

        return response()->json([
            'night_id' => $trackedNight->id,
            'recording_url' => url(Storage::disk('public')->url($path)),
        ]);
    }

    public function complete(Request $request, TrackedNight $trackedNight): JsonResponse
    {
        $payload = $request->validate([
            'device_id' => ['nullable', 'string', 'max:120'],
            'ended_at' => ['nullable', 'date'],
            'recording_duration_seconds' => ['nullable', 'integer', 'min:1'],
            'metadata' => ['nullable', 'array'],
        ]);

        $this->authorizeNightAccess($request, $trackedNight, $payload['device_id'] ?? null);

        $endedAt = isset($payload['ended_at'])
            ? CarbonImmutable::parse($payload['ended_at'])
            : now()->toImmutable();

        $trackedNight->update([
            'ended_at' => $endedAt,
            'recording_duration_seconds' => $payload['recording_duration_seconds'] ?? $trackedNight->recording_duration_seconds,
            'status' => 'completed',
            'metadata' => array_merge($trackedNight->metadata ?? [], $payload['metadata'] ?? []),
        ]);

        $nightPayload = $this->builder->analyzeAndPersist($trackedNight->fresh());

        return response()->json([
            'night' => $nightPayload,
        ]);
    }

    private function resolveListener(string $deviceId): Listener
    {
        $listener = Listener::query()->firstOrCreate(
            ['device_id' => $deviceId],
            ['last_active_at' => now()]
        );

        if (request()->user() && $listener->user_id === null) {
            $listener->forceFill([
                'user_id' => request()->user()->id,
                'last_active_at' => now(),
            ])->save();
        }

        return $listener;
    }

    private function authorizeNightAccess(Request $request, TrackedNight $trackedNight, ?string $deviceId = null): void
    {
        $user = $request->user();
        if ($user && $trackedNight->listener?->user_id === $user->id) {
            return;
        }

        if ($deviceId !== null && $trackedNight->listener?->device_id === $deviceId) {
            return;
        }

        abort(403);
    }

    private function serializeActiveNight(TrackedNight $night): array
    {
        return [
            'night_id' => $night->id,
            'status' => $night->status,
            'tracked_date' => optional($night->tracked_date)->toDateString(),
            'bedtime' => $night->started_at?->toIso8601String(),
            'wake_time' => $night->ended_at?->toIso8601String(),
            'time_asleep_minutes' => 0,
            'sleep_goal_minutes' => $night->sleep_goal_minutes,
            'quality_score' => 0,
            'summary_cards' => [],
            'sleep_phases' => [
                'timeline' => [],
                'totals' => [
                    'awake' => 0,
                    'dream' => 0,
                    'light' => 0,
                    'deep' => 0,
                ],
                'focus_key' => 'light',
                'focus_title' => 'Light',
                'focus_body' => '',
                'key_insights' => '',
            ],
            'sound_detections' => [],
            'recordings' => [],
            'recommended_tracks' => [],
        ];
    }
}
