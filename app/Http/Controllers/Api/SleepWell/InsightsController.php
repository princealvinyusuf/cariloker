<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\Listener;
use App\Models\SleepWell\SleepSession;
use App\Models\SleepWell\TrackedNight;
use App\Services\SleepWell\NightInsightsBuilder;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;

class InsightsController extends Controller
{
    public function __construct(
        private readonly NightInsightsBuilder $builder,
    ) {
    }

    public function forCurrentUser(): JsonResponse
    {
        $listener = Listener::query()
            ->where('user_id', request()->user()->id)
            ->latest('last_active_at')
            ->first();

        return response()->json($this->buildInsightsPayload($listener));
    }

    public function show(string $deviceId): JsonResponse
    {
        $listener = Listener::query()->where('device_id', $deviceId)->first();

        return response()->json($this->buildInsightsPayload($listener));
    }

    private function buildInsightsPayload(?Listener $listener): array
    {
        if (!$listener) {
            return [
                'usage_frequency_last_7_days' => 0,
                'consistency_score' => 0,
                'average_duration_minutes' => 0,
                'available_dates' => [],
                'nights' => [],
            ];
        }

        $since = CarbonImmutable::now()->subDays(7);
        $sessions = SleepSession::query()
            ->where('listener_id', $listener->id)
            ->where('started_at', '>=', $since)
            ->where('status', 'completed')
            ->get();

        $daysUsed = $sessions
            ->groupBy(fn (SleepSession $session) => $session->started_at?->toDateString())
            ->count();

        $averageDurationMinutes = $sessions->count() > 0
            ? (int) round($sessions->avg('duration_seconds') / 60)
            : 0;

        $consistencyScore = min(100, (int) round(($daysUsed / 7) * 100));

        $nights = TrackedNight::query()
            ->where('listener_id', $listener->id)
            ->whereIn('status', ['completed', 'analyzed', 'uploaded'])
            ->orderByDesc('tracked_date')
            ->orderByDesc('started_at')
            ->limit(14)
            ->get()
            ->map(fn (TrackedNight $night) => $this->builder->toApiPayload($night))
            ->values()
            ->all();

        $availableDates = TrackedNight::query()
            ->where('listener_id', $listener->id)
            ->whereNotNull('tracked_date')
            ->orderByDesc('tracked_date')
            ->limit(31)
            ->pluck('tracked_date')
            ->map(fn ($date) => CarbonImmutable::parse($date)->toDateString())
            ->values()
            ->all();

        return [
            'usage_frequency_last_7_days' => $daysUsed,
            'consistency_score' => $consistencyScore,
            'average_duration_minutes' => $averageDurationMinutes,
            'available_dates' => $availableDates,
            'nights' => $nights,
        ];
    }
}
