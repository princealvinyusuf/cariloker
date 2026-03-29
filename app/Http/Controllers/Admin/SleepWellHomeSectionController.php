<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\HomeSection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SleepWellHomeSectionController extends Controller
{
    public function index(Request $request): View
    {
        $selectedScreen = (string) $request->query('screen', 'all');
        $screenOptions = $this->screenOptions();

        if (!array_key_exists($selectedScreen, $screenOptions)) {
            $selectedScreen = 'all';
        }

        $sectionsQuery = HomeSection::query()->orderBy('sort_order');
        $this->applyScreenFilter($sectionsQuery, $selectedScreen);

        $sections = $sectionsQuery
            ->paginate(20)
            ->appends(['screen' => $selectedScreen]);

        return view('admin.sleepwell.home-sections.index', [
            'sections' => $sections,
            'selectedScreen' => $selectedScreen,
            'screenOptions' => $screenOptions,
        ]);
    }

    public function create(): View
    {
        return view('admin.sleepwell.home-sections.form', [
            'section' => new HomeSection(),
            'method' => 'POST',
            'action' => route('admin.sleepwell.home-sections.store'),
            'title' => 'Create Home Section',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        HomeSection::query()->create($payload);

        return redirect()->route('admin.sleepwell.home-sections.index')
            ->with('status', 'Home section created.');
    }

    public function edit(HomeSection $section): View
    {
        return view('admin.sleepwell.home-sections.form', [
            'section' => $section,
            'method' => 'PUT',
            'action' => route('admin.sleepwell.home-sections.update', $section),
            'title' => 'Edit Home Section',
        ]);
    }

    public function update(Request $request, HomeSection $section): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        $section->update($payload);

        return redirect()->route('admin.sleepwell.home-sections.index')
            ->with('status', 'Home section updated.');
    }

    public function destroy(HomeSection $section): RedirectResponse
    {
        $section->delete();

        return redirect()->route('admin.sleepwell.home-sections.index')
            ->with('status', 'Home section deleted.');
    }

    private function validatedPayload(Request $request): array
    {
        return $request->validate([
            'section_key' => ['required', 'string', 'max:80'],
            'title' => ['nullable', 'string', 'max:160'],
            'subtitle' => ['nullable', 'string', 'max:300'],
            'section_type' => ['required', 'in:hero_carousel,grid,horizontal,top_ranked,chips,promo'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function applyScreenFilter($query, string $selectedScreen): void
    {
        if ($selectedScreen === 'all') {
            return;
        }

        if ($selectedScreen === 'home') {
            $query->where(function ($inner) {
                foreach ($this->homeSectionKeys() as $sectionKey) {
                    $inner->orWhere('section_key', $sectionKey);
                }
            });

            return;
        }

        if ($selectedScreen === 'settings') {
            $query->where('section_key', 'like', 'profile_settings_%');

            return;
        }

        $query->where('section_key', 'like', $selectedScreen . '_%');
    }

    private function screenOptions(): array
    {
        return [
            'all' => 'All Screens',
            'home' => 'Home',
            'sounds' => 'Sounds',
            'routine' => 'Routine',
            'insight' => 'Insight',
            'saved' => 'Saved',
            'profile' => 'Profile',
            'settings' => 'Settings',
        ];
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
