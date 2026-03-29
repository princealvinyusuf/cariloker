<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\HomeSection;
use App\Models\SleepWell\Listener;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeFeedController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $screen = (string) $request->query('screen', '');
        $goal = trim((string) $request->query('goal', ''));
        $timeSegment = trim((string) $request->query('time_segment', ''));
        $deviceId = trim((string) $request->query('device_id', ''));
        $now = now();
        $listener = $deviceId === ''
            ? null
            : Listener::query()->where('device_id', $deviceId)->first();
        $preferredCategories = collect($listener?->preferred_categories ?? [])
            ->filter(fn ($v) => is_string($v) && trim($v) !== '')
            ->map(fn ($v) => trim((string) $v))
            ->values()
            ->all();

        $sections = HomeSection::query()
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('unpublish_at')
                    ->orWhere('unpublish_at', '>', $now);
            })
            ->when($screen !== '', function ($q) use ($screen) {
                if ($screen === 'home') {
                    $homeKeys = [
                        'featured_content',
                        'explore_grid',
                        'promo_therapy',
                        'sleep_recorder',
                        'colored_noises',
                        'top_rated',
                        'quick_topics',
                        'discover_banner',
                        'try_something_else',
                        'curated_playlists',
                        'sleep_hypnosis',
                    ];
                    $q->whereIn('section_key', $homeKeys);
                } elseif ($screen === 'settings') {
                    $q->where('section_key', 'like', 'profile_settings_%');
                } else {
                    $q->where('section_key', 'like', $screen . '_%');
                }
            })
            ->with(['items' => fn ($q) => $q
                ->where('is_active', true)
                ->where(function ($inner) use ($now) {
                    $inner->whereNull('publish_at')
                        ->orWhere('publish_at', '<=', $now);
                })
                ->where(function ($inner) use ($now) {
                    $inner->whereNull('unpublish_at')
                        ->orWhere('unpublish_at', '>', $now);
                })
                ->when($goal !== '', function ($inner) use ($goal) {
                    $inner->where(function ($goals) use ($goal) {
                        $goals->whereNull('meta->goals')
                            ->orWhereJsonContains('meta->goals', $goal);
                    });
                })
                ->when($timeSegment !== '', function ($inner) use ($timeSegment) {
                    $inner->where(function ($segments) use ($timeSegment) {
                        $segments->whereNull('meta->time_segments')
                            ->orWhereJsonContains('meta->time_segments', $timeSegment);
                    });
                })
                ->when(!empty($preferredCategories), function ($inner) use ($preferredCategories) {
                    $inner->where(function ($pref) use ($preferredCategories) {
                        $pref->whereNull('meta->categories');
                        foreach ($preferredCategories as $category) {
                            $pref->orWhereJsonContains('meta->categories', $category);
                        }
                    });
                })
                ->orderBy('sort_order')
            ])
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'sections' => $sections,
        ]);
    }
}
