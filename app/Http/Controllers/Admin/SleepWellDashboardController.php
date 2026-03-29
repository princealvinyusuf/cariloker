<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\HomeItem;
use App\Models\SleepWell\HomeSection;
use App\Models\SleepWell\Listener;
use App\Models\SleepWell\SleepSession;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SleepWellDashboardController extends Controller
{
    public function index(): View
    {
        $totalListeners = Listener::query()->count();
        $activeToday = SleepSession::query()
            ->whereDate('started_at', today())
            ->distinct('listener_id')
            ->count('listener_id');

        $completedSessions = SleepSession::query()
            ->where('status', 'completed')
            ->whereNotNull('ended_at')
            ->count();

        $avgDurationMinutes = (int) round(
            (SleepSession::query()->where('status', 'completed')->avg('duration_seconds') ?? 0) / 60
        );

        return view('admin.sleepwell.dashboard', [
            'totalListeners' => $totalListeners,
            'activeToday' => $activeToday,
            'completedSessions' => $completedSessions,
            'avgDurationMinutes' => $avgDurationMinutes,
        ]);
    }

    public function contentHub(Request $request, string $screen): View
    {
        $screenOptions = $this->screenOptions();
        if (!array_key_exists($screen, $screenOptions)) {
            abort(404);
        }

        $sectionsQuery = HomeSection::query()->orderBy('sort_order');
        $this->applyScreenFilterToSections($sectionsQuery, $screen);
        $sections = $sectionsQuery->withCount('items')->get();

        $itemsQuery = HomeItem::query()->with('section')->orderBy('sort_order');
        $this->applyScreenFilterToItems($itemsQuery, $screen);
        $items = $itemsQuery->paginate(20)->appends(['screen' => $screen]);

        return view('admin.sleepwell.content-hub', [
            'screen' => $screen,
            'screenLabel' => $screenOptions[$screen],
            'screenOptions' => $screenOptions,
            'sections' => $sections,
            'items' => $items,
            'sectionCount' => $sections->count(),
            'itemCount' => $items->total(),
        ]);
    }

    private function screenOptions(): array
    {
        return [
            'home' => 'Home',
            'sounds' => 'Sounds',
            'routine' => 'Routine',
            'insight' => 'Insight',
            'saved' => 'Saved',
            'profile' => 'Profile',
            'settings' => 'Settings',
        ];
    }

    private function applyScreenFilterToSections($query, string $screen): void
    {
        if ($screen === 'home') {
            $query->where(function ($inner) {
                foreach ($this->homeSectionKeys() as $sectionKey) {
                    $inner->orWhere('section_key', $sectionKey);
                }
            });

            return;
        }

        if ($screen === 'settings') {
            $query->where('section_key', 'like', 'profile_settings_%');

            return;
        }

        $query->where('section_key', 'like', $screen . '_%');
    }

    private function applyScreenFilterToItems($query, string $screen): void
    {
        $query->whereHas('section', function ($sectionQuery) use ($screen) {
            if ($screen === 'home') {
                $sectionQuery->where(function ($inner) {
                    foreach ($this->homeSectionKeys() as $sectionKey) {
                        $inner->orWhere('section_key', $sectionKey);
                    }
                });

                return;
            }

            if ($screen === 'settings') {
                $sectionQuery->where('section_key', 'like', 'profile_settings_%');

                return;
            }

            $sectionQuery->where('section_key', 'like', $screen . '_%');
        });
    }

    private function homeSectionKeys(): array
    {
        return [
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
    }
}
