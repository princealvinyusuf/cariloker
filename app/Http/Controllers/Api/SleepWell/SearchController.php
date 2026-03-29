<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\AudioTrack;
use App\Models\SleepWell\Listener;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));
        $limit = min(30, max(1, (int) $request->query('limit', 12)));
        $deviceId = trim((string) $request->query('device_id', ''));
        $goal = trim((string) $request->query('goal', ''));
        $timeSegment = trim((string) $request->query('time_segment', ''));
        $listener = $deviceId === ''
            ? null
            : Listener::query()->where('device_id', $deviceId)->first();
        $preferredCategories = collect($listener?->preferred_categories ?? [])
            ->filter(fn ($v) => is_string($v) && trim($v) !== '')
            ->map(fn ($v) => strtolower(trim((string) $v)))
            ->values()
            ->all();
        $prefersTalking = $listener?->prefers_talking;

        $tracks = AudioTrack::query()
            ->where('is_active', true)
            ->when($query !== '', function ($builder) use ($query) {
                $builder
                    ->where('title', 'like', '%' . $query . '%')
                    ->orWhere('category', 'like', '%' . $query . '%')
                    ->orWhere('sound_type', 'like', '%' . $query . '%');
            })
            ->limit(200)
            ->get();

        $queryLower = strtolower($query);
        $goalLower = strtolower($goal);
        $timeLower = strtolower($timeSegment);

        $ranked = $tracks->sortByDesc(function (AudioTrack $track) use (
            $queryLower,
            $preferredCategories,
            $prefersTalking,
            $goalLower,
            $timeLower
        ) {
            $score = (int) ($track->plays_count ?? 0);
            $title = strtolower((string) $track->title);
            $category = strtolower((string) $track->category);
            $soundType = strtolower((string) $track->sound_type);

            if ($queryLower !== '' && str_contains($title, $queryLower)) {
                $score += 120;
            }
            if ($queryLower !== '' && str_contains($category, $queryLower)) {
                $score += 60;
            }
            if ($queryLower !== '' && str_contains($soundType, $queryLower)) {
                $score += 45;
            }
            if (!empty($preferredCategories) && in_array($category, $preferredCategories, true)) {
                $score += 90;
            }
            if ($prefersTalking !== null && (bool) $track->talking === (bool) $prefersTalking) {
                $score += 25;
            }
            if ($goalLower !== '' && str_contains($title, $goalLower)) {
                $score += 20;
            }
            if ($timeLower !== '' && str_contains($soundType, $timeLower)) {
                $score += 15;
            }

            return $score;
        })->take($limit)->values();

        return response()->json([
            'results' => $ranked,
        ]);
    }
}
