<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\HomeSection;
use App\Support\SleepWellAuditLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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
        $section = HomeSection::query()->create($payload);
        SleepWellAuditLogger::log($request, 'create', $section, $payload);

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
        $before = $section->toArray();
        $section->update($payload);
        SleepWellAuditLogger::log($request, 'update', $section, [
            'before' => $before,
            'after' => $section->fresh()?->toArray(),
        ]);

        return redirect()->route('admin.sleepwell.home-sections.index')
            ->with('status', 'Home section updated.');
    }

    public function destroy(HomeSection $section): RedirectResponse
    {
        $snapshot = $section->toArray();
        $section->delete();
        SleepWellAuditLogger::log(request(), 'delete', $section, ['before' => $snapshot]);

        return redirect()->route('admin.sleepwell.home-sections.index')
            ->with('status', 'Home section deleted.');
    }

    public function export(Request $request): JsonResponse
    {
        $selectedScreen = (string) $request->query('screen', 'all');
        $query = HomeSection::query()->orderBy('sort_order');
        $this->applyScreenFilter($query, $selectedScreen);

        return response()->json([
            'sections' => $query->get(),
        ]);
    }

    public function import(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'sections_json' => ['required', 'string'],
        ]);
        $decoded = json_decode($payload['sections_json'], true);
        if (!is_array($decoded)) {
            return back()->with('status', 'Invalid JSON payload.');
        }

        foreach ($decoded as $row) {
            if (!is_array($row) || !isset($row['section_key'])) {
                continue;
            }
            $data = [
                'title' => $row['title'] ?? null,
                'subtitle' => $row['subtitle'] ?? null,
                'section_type' => $row['section_type'] ?? 'horizontal',
                'sort_order' => (int) ($row['sort_order'] ?? 0),
                'is_active' => (bool) ($row['is_active'] ?? true),
                'publish_at' => $row['publish_at'] ?? null,
                'unpublish_at' => $row['unpublish_at'] ?? null,
            ];
            HomeSection::query()->updateOrCreate(
                ['section_key' => (string) $row['section_key']],
                $data
            );
        }

        return back()->with('status', 'Sections imported.');
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
            'publish_at' => ['nullable', 'date'],
            'unpublish_at' => ['nullable', 'date', 'after:publish_at'],
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
