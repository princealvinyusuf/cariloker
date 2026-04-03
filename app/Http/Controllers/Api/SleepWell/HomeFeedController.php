<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\HomeSection;
use App\Models\SleepWell\Listener;
use App\Models\SleepWell\SavedItem;
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

        $sectionsPayload = $sections->map(function (HomeSection $section) {
            return [
                'id' => $section->id,
                'section_key' => $section->section_key,
                'title' => $section->title,
                'subtitle' => $section->subtitle,
                'section_type' => $section->section_type,
                'sort_order' => $section->sort_order,
                'meta' => $section->meta ?? [],
                'items' => $section->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'subtitle' => $item->subtitle,
                        'tag' => $item->tag,
                        'image_url' => $item->image_url,
                        'icon_url' => $item->icon_url,
                        'cta_label' => $item->cta_label,
                        'meta' => $item->meta ?? [],
                    ];
                })->values()->all(),
            ];
        })->values()->all();

        $user = $request->user('sanctum');
        if ($user) {
            $sectionsPayload = $this->personalizeSavedSections(
                $sectionsPayload,
                $user->id,
                [
                    'name' => $user->name,
                    'headline' => $user->headline,
                    'bio' => $user->bio,
                ],
            );
        }

        return response()->json([
            'sections' => $sectionsPayload,
        ]);
    }

    private function personalizeSavedSections(
        array $sections,
        int $userId,
        array $profileSignals = [],
    ): array {
        $savedItems = SavedItem::query()
            ->where('user_id', $userId)
            ->latest('updated_at')
            ->get();

        if ($savedItems->isEmpty()) {
            return $sections;
        }

        $savedTitles = $savedItems
            ->pluck('title')
            ->filter(fn ($value) => is_string($value) && trim($value) !== '')
            ->map(fn ($value) => strtolower(trim((string) $value)))
            ->values()
            ->all();

        $signalWords = collect([
            $profileSignals['name'] ?? null,
            $profileSignals['headline'] ?? null,
            $profileSignals['bio'] ?? null,
            ...$savedTitles,
        ])
            ->filter(fn ($value) => is_string($value) && trim($value) !== '')
            ->flatMap(function (string $value) {
                return preg_split('/\s+/', strtolower(trim($value))) ?: [];
            })
            ->filter(fn ($word) => is_string($word) && strlen($word) >= 4)
            ->values()
            ->all();

        $savedByType = $savedItems->groupBy('item_type');

        foreach ($sections as &$section) {
            if (!is_array($section) || !isset($section['section_key'])) {
                continue;
            }
            $sectionKey = (string) $section['section_key'];
            $items = is_array($section['items'] ?? null) ? $section['items'] : [];

            if ($sectionKey === 'saved_favorites' && $savedByType->has('favorites')) {
                $section['items'] = $this->mapSavedItemsToSectionItems(
                    $savedByType->get('favorites')->take(18)->all(),
                    'favorites'
                );
                continue;
            }

            if ($sectionKey === 'saved_recently_played' && $savedByType->has('recently_played')) {
                $recentlyPlayed = $savedByType->get('recently_played')
                    ->sortByDesc(function (SavedItem $item) {
                        return $item->last_played_at ?? $item->updated_at;
                    })
                    ->take(18)
                    ->values()
                    ->all();
                $section['items'] = $this->mapSavedItemsToSectionItems(
                    $recentlyPlayed,
                    'recently_played'
                );
                continue;
            }

            if ($sectionKey === 'saved_playlists' && $savedByType->has('playlists')) {
                $section['items'] = $this->mapSavedItemsToSectionItems(
                    $savedByType->get('playlists')->take(18)->all(),
                    'playlists'
                );
                continue;
            }

            if ($sectionKey === 'saved_suggestions' && !empty($items)) {
                $section['items'] = $this->scoreSuggestionItems($items, $signalWords);
                if (!empty(trim((string) ($profileSignals['headline'] ?? '')))) {
                    $section['title'] = trim((string) $profileSignals['headline']) . ' picks';
                }
            }
        }

        return $sections;
    }

    private function mapSavedItemsToSectionItems(array $savedItems, string $type): array
    {
        return collect($savedItems)->map(function (SavedItem $item) use ($type) {
            $meta = is_array($item->meta) ? $item->meta : [];
            $meta['source_item_type'] = $type;
            $meta['source_item_ref'] = $item->item_ref;
            if (empty($meta['action'])) {
                $meta['action'] = $type === 'playlists' ? 'arrow' : ($type === 'favorites' ? 'heart' : 'more');
            }

            return [
                'title' => $item->title,
                'subtitle' => $item->subtitle,
                'tag' => null,
                'image_url' => null,
                'icon_url' => null,
                'cta_label' => null,
                'meta' => $meta,
                'updated_at' => optional($item->updated_at)->toIso8601String(),
                'last_played_at' => optional($item->last_played_at)->toIso8601String(),
            ];
        })->values()->all();
    }

    private function scoreSuggestionItems(array $items, array $signalWords): array
    {
        if (empty($signalWords)) {
            return $items;
        }

        $scored = collect($items)->map(function (array $item) use ($signalWords) {
            $title = strtolower((string) ($item['title'] ?? ''));
            $subtitle = strtolower((string) ($item['subtitle'] ?? ''));
            $score = 0;
            foreach ($signalWords as $signalWord) {
                if (!is_string($signalWord) || $signalWord === '') {
                    continue;
                }
                if (str_contains($title, $signalWord)) {
                    $score += 3;
                }
                if (str_contains($subtitle, $signalWord)) {
                    $score += 2;
                }
            }
            $meta = is_array($item['meta'] ?? null) ? $item['meta'] : [];
            if ($score > 0) {
                $meta['personalization_reason'] = 'Matched your profile and listening history';
            }
            $item['meta'] = $meta;
            $item['_score'] = $score;
            return $item;
        })->sortByDesc('_score')->values();

        return $scored->map(function (array $item) {
            unset($item['_score']);
            return $item;
        })->all();
    }
}
