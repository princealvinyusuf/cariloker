<?php

namespace App\Services\SleepWell;

use App\Models\SleepWell\AudioTrack;
use App\Models\SleepWell\TrackedNight;
use App\Models\SleepWell\TrackedNightDetection;
use App\Models\SleepWell\TrackedNightRecording;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NightInsightsBuilder
{
    public function analyzeAndPersist(TrackedNight $night): array
    {
        $night->loadMissing(['preferredTrack', 'detections', 'recordings']);

        $startedAt = CarbonImmutable::instance($night->started_at ?? now());
        $endedAt = $night->ended_at
            ? CarbonImmutable::instance($night->ended_at)
            : $startedAt->addSeconds(max(1, $night->recording_duration_seconds));

        $durationSeconds = max(
            60,
            $night->recording_duration_seconds > 0
                ? $night->recording_duration_seconds
                : $startedAt->diffInSeconds($endedAt)
        );
        $durationMinutes = max(1, (int) round($durationSeconds / 60));
        $goalMinutes = max(360, min(600, (int) $night->sleep_goal_minutes));

        $qualityScore = $this->computeQualityScore($durationMinutes, $goalMinutes, $night);
        $phaseTotals = $this->buildPhaseTotals($durationMinutes, $qualityScore);
        $timeline = $this->buildTimeline($phaseTotals, $durationMinutes);
        $detections = $this->buildDetections($night, $durationMinutes);
        $recordings = $this->buildRecordings($night, $durationSeconds, $detections);
        $summaryCards = $this->buildSummaryCards(
            night: $night,
            qualityScore: $qualityScore,
            durationMinutes: $durationMinutes,
            goalMinutes: $goalMinutes,
            detections: $detections,
        );

        $recommendedTracks = AudioTrack::query()
            ->whereNotNull('subtitle')
            ->limit(3)
            ->get()
            ->map(fn (AudioTrack $track) => [
                'id' => $track->id,
                'title' => $track->title,
                'subtitle' => $track->subtitle,
            ])
            ->all();

        $focus = $this->buildPhaseFocus($phaseTotals, $qualityScore);

        $payload = [
            'night_id' => $night->id,
            'status' => 'analyzed',
            'tracked_date' => optional($night->tracked_date)->toDateString() ?? $startedAt->toDateString(),
            'bedtime' => $startedAt->toIso8601String(),
            'wake_time' => $endedAt->toIso8601String(),
            'time_asleep_minutes' => $durationMinutes,
            'sleep_goal_minutes' => $goalMinutes,
            'quality_score' => $qualityScore,
            'summary_cards' => $summaryCards,
            'sleep_phases' => [
                'timeline' => $timeline,
                'totals' => $phaseTotals,
                'focus_key' => $focus['focus_key'],
                'focus_title' => $focus['focus_title'],
                'focus_body' => $focus['focus_body'],
                'key_insights' => $focus['key_insights'],
            ],
            'sound_detections' => $detections,
            'recordings' => $recordings,
            'recommended_tracks' => $recommendedTracks,
        ];

        DB::transaction(function () use ($night, $payload, $detections, $recordings, $durationSeconds): void {
            $night->detections()->delete();
            $night->recordings()->delete();

            foreach ($detections as $detection) {
                TrackedNightDetection::query()->create([
                    'tracked_night_id' => $night->id,
                    'detection_key' => $detection['key'],
                    'label' => $detection['label'],
                    'occurrence_count' => $detection['count'],
                    'total_seconds' => $detection['total_seconds'],
                    'confidence_score' => $detection['confidence_score'],
                    'metadata' => [
                        'emoji' => $detection['emoji'],
                        'status' => $detection['status'],
                    ],
                ]);
            }

            foreach ($recordings as $recording) {
                TrackedNightRecording::query()->create([
                    'tracked_night_id' => $night->id,
                    'label' => $recording['label'],
                    'detection_key' => $recording['detection_key'],
                    'start_second' => $recording['start_second'],
                    'duration_seconds' => $recording['duration_seconds'],
                    'confidence_score' => $recording['confidence_score'],
                    'source_path' => $night->recording_path,
                    'metadata' => [
                        'description' => $recording['description'],
                        'occurred_at' => $recording['occurred_at'],
                    ],
                ]);
            }

            $night->update([
                'status' => 'analyzed',
                'recording_duration_seconds' => $durationSeconds,
                'insights_payload' => $payload,
                'last_analyzed_at' => now(),
            ]);
        });

        return $payload;
    }

    public function toApiPayload(TrackedNight $night): array
    {
        $payload = $night->insights_payload ?? [];
        if ($payload !== []) {
            return $payload;
        }

        return $this->analyzeAndPersist($night);
    }

    private function computeQualityScore(int $durationMinutes, int $goalMinutes, TrackedNight $night): int
    {
        $goalDelta = abs($goalMinutes - $durationMinutes);
        $goalPenalty = min(28, (int) round($goalDelta / 12));
        $seed = $this->nightSeed($night);
        $baseScore = 92 - $goalPenalty - ($seed % 10);

        return max(45, min(98, $baseScore));
    }

    private function buildPhaseTotals(int $durationMinutes, int $qualityScore): array
    {
        $awakeMinutes = max(0, (int) round($durationMinutes * (0.04 + ((100 - $qualityScore) / 550))));
        $dreamMinutes = max(20, (int) round($durationMinutes * 0.15));
        $deepMinutes = max(30, (int) round($durationMinutes * (0.1 + ($qualityScore / 1000))));
        $lightMinutes = max(0, $durationMinutes - $awakeMinutes - $dreamMinutes - $deepMinutes);

        return [
            'awake' => $awakeMinutes,
            'dream' => $dreamMinutes,
            'light' => $lightMinutes,
            'deep' => $deepMinutes,
        ];
    }

    private function buildTimeline(array $phaseTotals, int $durationMinutes): array
    {
        $segments = max(12, min(20, (int) floor($durationMinutes / 40)));
        $pattern = [
            'light', 'awake', 'light', 'deep', 'deep',
            'light', 'dream', 'light', 'dream', 'light',
        ];
        $timeline = [];
        $phaseUsage = [
            'awake' => 0,
            'dream' => 0,
            'light' => 0,
            'deep' => 0,
        ];

        for ($index = 0; $index < $segments; $index++) {
            $phase = $pattern[$index % count($pattern)];
            if ($phaseUsage[$phase] >= $phaseTotals[$phase]) {
                $remaining = collect($phaseTotals)
                    ->map(fn ($minutes, $key) => $minutes - $phaseUsage[$key])
                    ->filter(fn ($value) => $value > 0)
                    ->sortDesc();
                $phase = $remaining->keys()->first() ?? 'light';
            }

            $segmentMinutes = ($index === $segments - 1)
                ? max(1, $durationMinutes - array_sum(array_column($timeline, 'minutes')))
                : max(1, (int) floor($durationMinutes / $segments));

            $phaseUsage[$phase] += $segmentMinutes;
            $timeline[] = [
                'minute_offset' => array_sum(array_column($timeline, 'minutes')),
                'minutes' => $segmentMinutes,
                'phase' => $phase,
            ];
        }

        return $timeline;
    }

    private function buildDetections(TrackedNight $night, int $durationMinutes): array
    {
        $source = $this->nightSeed($night);
        $durationFactor = max(1, (int) floor($durationMinutes / 120));
        $catalog = [
            ['key' => 'snoring', 'label' => 'Snoring', 'emoji' => '😴'],
            ['key' => 'noise', 'label' => 'Noise', 'emoji' => '💥'],
            ['key' => 'music', 'label' => 'Music', 'emoji' => '💿'],
            ['key' => 'traffic', 'label' => 'Traffic', 'emoji' => '🚦'],
            ['key' => 'talking', 'label' => 'Talking', 'emoji' => '💬'],
        ];

        $results = [];
        foreach ($catalog as $index => $item) {
            $value = ($source >> ($index * 3)) & 7;
            $count = $value % max(1, $durationFactor);
            $totalSeconds = $count === 0 ? 0 : ($count * 60 * (($index % 2) + 1));
            $results[] = [
                'key' => $item['key'],
                'label' => $item['label'],
                'emoji' => $item['emoji'],
                'count' => $count,
                'status' => $count == 0 ? 'None' : ($count === 1 ? '1 clip' : "{$count} clips"),
                'total_seconds' => $totalSeconds,
                'minutes' => (int) round($totalSeconds / 60),
                'confidence_score' => $count == 0 ? 0 : 60 + (($source + $index) % 30),
            ];
        }

        return $results;
    }

    private function buildRecordings(TrackedNight $night, int $durationSeconds, array $detections): array
    {
        if ($durationSeconds < 900 || empty($night->recording_path)) {
            return [];
        }

        $activeDetections = array_values(array_filter($detections, fn (array $item) => $item['count'] > 0));
        if ($activeDetections === []) {
            return [];
        }

        $recordings = [];
        foreach (array_slice($activeDetections, 0, 4) as $index => $detection) {
            $clipDuration = 20 + (($this->nightSeed($night) + $index) % 35);
            $maxStart = max(0, $durationSeconds - $clipDuration - 1);
            $startSecond = $maxStart === 0 ? 0 : ($this->nightSeed($night) * ($index + 1)) % $maxStart;
            $occurredAt = CarbonImmutable::instance($night->started_at ?? now())->addSeconds($startSecond);
            $recordings[] = [
                'id' => ($night->id * 10) + $index + 1,
                'label' => $detection['label'],
                'description' => $detection['count'] > 1
                    ? 'Detected multiple times during the night.'
                    : 'Detected once during the night.',
                'detection_key' => $detection['key'],
                'start_second' => $startSecond,
                'duration_seconds' => $clipDuration,
                'confidence_score' => $detection['confidence_score'],
                'occurred_at' => $occurredAt->toIso8601String(),
                'source_url' => $this->publicUrl($night->recording_path),
            ];
        }

        return $recordings;
    }

    private function buildSummaryCards(
        TrackedNight $night,
        int $qualityScore,
        int $durationMinutes,
        int $goalMinutes,
        array $detections,
    ): array {
        $durationLabel = $this->formatHoursAndMinutes($durationMinutes);
        $goalLabel = $this->formatHoursAndMinutes($goalMinutes);
        $topDetection = collect($detections)->sortByDesc('count')->first();

        return [
            [
                'key' => 'quality',
                'eyebrow' => 'SLEEP QUALITY',
                'title' => $qualityScore >= 85 ? 'Nailed it!' : 'A restorative night',
                'subtitle' => "Swipe left to see last night's highlights",
                'metric' => (string) $qualityScore,
            ],
            [
                'key' => 'total_sleep',
                'eyebrow' => "$durationLabel OUT OF $goalLabel",
                'title' => 'Total Sleep Time',
                'subtitle' => 'Another night, another win. You are building steady sleep habits.',
                'metric' => $durationLabel,
            ],
            [
                'key' => 'snoring',
                'eyebrow' => (($topDetection['count'] ?? 0) > 0 ? 'SOUND DETECTED' : 'NO SOUND SPIKES'),
                'title' => ucfirst($topDetection['label'] ?? 'Quiet night'),
                'subtitle' => (($topDetection['count'] ?? 0) > 0)
                    ? 'A few sleep sounds were detected while you rested.'
                    : 'Minimal disturbances were detected during the night.',
                'metric' => (string) ($topDetection['count'] ?? 0),
            ],
            [
                'key' => 'schedule',
                'eyebrow' => 'BEDTIME RHYTHM',
                'title' => 'Consistency matters',
                'subtitle' => 'Your tracked bedtime and wake-up rhythm continue shaping better sleep.',
                'metric' => optional($night->tracked_date)->format('M j') ?? 'Tonight',
            ],
        ];
    }

    private function buildPhaseFocus(array $phaseTotals, int $qualityScore): array
    {
        $focusKey = collect($phaseTotals)->sortDesc()->keys()->first() ?? 'light';
        $percentage = $phaseTotals[$focusKey] == 0
            ? 0
            : (int) round(($phaseTotals[$focusKey] / max(1, array_sum($phaseTotals))) * 100);

        $body = match ($focusKey) {
            'awake' => "You were awake for {$percentage}% of the night. Keeping a stable wind-down can help lower that over time.",
            'dream' => "Dream sleep made up {$percentage}% of the night, showing healthy REM recovery.",
            'deep' => "Deep sleep made up {$percentage}% of the night, which supports physical restoration.",
            default => "Light sleep made up {$percentage}% of the night, giving you a steady overnight rhythm.",
        };

        return [
            'focus_key' => $focusKey,
            'focus_title' => ucfirst($focusKey),
            'focus_body' => $body,
            'key_insights' => $qualityScore >= 85
                ? 'Your night stayed close to your sleep goal, with a stable progression through the main sleep phases.'
                : 'Your night showed a few interruptions, but the overall structure still points toward consistent recovery.',
        ];
    }

    private function formatHoursAndMinutes(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        if ($hours <= 0) {
            return "{$remaining}m";
        }
        if ($remaining == 0) {
            return "{$hours}h";
        }

        return "{$hours}h {$remaining}m";
    }

    private function publicUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        return url(Storage::disk('public')->url($path));
    }

    private function nightSeed(TrackedNight $night): int
    {
        return abs(crc32("{$night->id}:{$night->started_at?->toIso8601String()}:{$night->recording_path}"));
    }
}
