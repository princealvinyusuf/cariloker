<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\Listener;
use App\Models\SleepWell\SessionEvent;
use App\Models\SleepWell\SleepSession;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function start(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'device_id' => ['required', 'string', 'max:120'],
            'mode' => ['required', 'in:player,sleep_now'],
            'entry_point' => ['nullable', 'string', 'max:50'],
            'device_local_date' => ['nullable', 'date'],
        ]);

        $listener = $this->resolveListener($payload['device_id']);

        $session = SleepSession::query()->create([
            'listener_id' => $listener->id,
            'started_at' => now(),
            'mode' => $payload['mode'],
            'entry_point' => $payload['entry_point'] ?? null,
            'device_local_date' => $payload['device_local_date'] ?? null,
            'status' => 'active',
        ]);

        return response()->json([
            'session_id' => $session->id,
            'started_at' => $session->started_at,
        ]);
    }

    public function event(Request $request, SleepSession $session): JsonResponse
    {
        $payload = $request->validate([
            'track_id' => ['nullable', 'integer'],
            'event_type' => ['required', 'string', 'max:50'],
            'position_seconds' => ['nullable', 'integer', 'min:0'],
            'metadata' => ['nullable', 'array'],
        ]);

        $event = SessionEvent::query()->create([
            'session_id' => $session->id,
            'track_id' => $payload['track_id'] ?? null,
            'event_type' => $payload['event_type'],
            'event_at' => now(),
            'position_seconds' => $payload['position_seconds'] ?? null,
            'metadata' => $payload['metadata'] ?? [],
        ]);

        return response()->json(['event_id' => $event->id], 201);
    }

    public function end(Request $request, SleepSession $session): JsonResponse
    {
        $payload = $request->validate([
            'status' => ['nullable', 'in:completed,abandoned'],
            'ended_at' => ['nullable', 'date'],
        ]);

        $endedAt = isset($payload['ended_at'])
            ? CarbonImmutable::parse($payload['ended_at'])
            : now()->toImmutable();
        $startedAt = CarbonImmutable::instance($session->started_at);

        $session->update([
            'ended_at' => $endedAt,
            'duration_seconds' => max(0, $startedAt->diffInSeconds($endedAt)),
            'status' => $payload['status'] ?? 'completed',
        ]);

        return response()->json([
            'session_id' => $session->id,
            'duration_seconds' => $session->duration_seconds,
            'status' => $session->status,
        ]);
    }

    private function resolveListener(string $deviceId): Listener
    {
        return Listener::query()->firstOrCreate(
            ['device_id' => $deviceId],
            ['last_active_at' => now()]
        );
    }
}
