<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\HomeItem;
use App\Models\SleepWell\HomeSection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SleepWellHomeItemController extends Controller
{
    public function index(Request $request): View
    {
        $selectedScreen = (string) $request->query('screen', 'all');
        $screenOptions = $this->screenOptions();

        if (!array_key_exists($selectedScreen, $screenOptions)) {
            $selectedScreen = 'all';
        }

        $itemsQuery = HomeItem::query()
            ->with('section')
            ->orderBy('sort_order');

        $this->applyScreenFilter($itemsQuery, $selectedScreen);

        $items = $itemsQuery
            ->paginate(30)
            ->appends(['screen' => $selectedScreen]);

        return view('admin.sleepwell.home-items.index', [
            'items' => $items,
            'selectedScreen' => $selectedScreen,
            'screenOptions' => $screenOptions,
        ]);
    }

    public function create(): View
    {
        return view('admin.sleepwell.home-items.form', [
            'item' => new HomeItem(),
            'sections' => HomeSection::query()->orderBy('sort_order')->get(),
            'method' => 'POST',
            'action' => route('admin.sleepwell.home-items.store'),
            'title' => 'Create Home Item',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        $payload['meta'] = $this->decodeMeta($payload['meta_json'] ?? null);
        $payload['image_url'] = $this->resolveImageUpload($request, 'image_file', (string) ($payload['image_url'] ?? ''));
        $payload['icon_url'] = $this->resolveImageUpload($request, 'icon_file', (string) ($payload['icon_url'] ?? ''));
        unset($payload['meta_json'], $payload['image_file'], $payload['icon_file']);

        HomeItem::query()->create($payload);

        return redirect()->route('admin.sleepwell.home-items.index')
            ->with('status', 'Home item created.');
    }

    public function edit(HomeItem $item): View
    {
        return view('admin.sleepwell.home-items.form', [
            'item' => $item,
            'sections' => HomeSection::query()->orderBy('sort_order')->get(),
            'method' => 'PUT',
            'action' => route('admin.sleepwell.home-items.update', $item),
            'title' => 'Edit Home Item',
        ]);
    }

    public function update(Request $request, HomeItem $item): RedirectResponse
    {
        $payload = $this->validatedPayload($request);
        $payload['meta'] = $this->decodeMeta($payload['meta_json'] ?? null);
        $payload['image_url'] = $this->resolveImageUpload($request, 'image_file', (string) ($payload['image_url'] ?? $item->image_url));
        $payload['icon_url'] = $this->resolveImageUpload($request, 'icon_file', (string) ($payload['icon_url'] ?? $item->icon_url));
        unset($payload['meta_json'], $payload['image_file'], $payload['icon_file']);

        $item->update($payload);

        return redirect()->route('admin.sleepwell.home-items.index')
            ->with('status', 'Home item updated.');
    }

    public function destroy(HomeItem $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('admin.sleepwell.home-items.index')
            ->with('status', 'Home item deleted.');
    }

    private function validatedPayload(Request $request): array
    {
        return $request->validate([
            'section_id' => ['required', 'integer', 'exists:sleepwell_home_sections,id'],
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:300'],
            'tag' => ['nullable', 'string', 'max:80'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'icon_url' => ['nullable', 'url', 'max:500'],
            'cta_label' => ['nullable', 'string', 'max:40'],
            'audio_track_id' => ['nullable', 'integer', 'exists:sleepwell_audio_tracks,id'],
            'meta_json' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'image_file' => ['nullable', 'image', 'max:6144'],
            'icon_file' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    private function decodeMeta(?string $metaJson): array
    {
        if (!$metaJson || trim($metaJson) === '') {
            return [];
        }
        $decoded = json_decode($metaJson, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function resolveImageUpload(Request $request, string $fileKey, string $fallback): string
    {
        if (!$request->hasFile($fileKey)) {
            return $fallback;
        }

        $path = $request->file($fileKey)->store('sleepwell/home-feed', 'public');

        return Storage::disk('public')->url($path);
    }

    private function applyScreenFilter($query, string $selectedScreen): void
    {
        if ($selectedScreen === 'all') {
            return;
        }

        $query->whereHas('section', function ($sectionQuery) use ($selectedScreen) {
            if ($selectedScreen === 'home') {
                $sectionQuery->where(function ($inner) {
                    foreach ($this->homeSectionKeys() as $sectionKey) {
                        $inner->orWhere('section_key', $sectionKey);
                    }
                });

                return;
            }

            if ($selectedScreen === 'settings') {
                $sectionQuery->where('section_key', 'like', 'profile_settings_%');

                return;
            }

            $sectionQuery->where('section_key', 'like', $selectedScreen . '_%');
        });
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
