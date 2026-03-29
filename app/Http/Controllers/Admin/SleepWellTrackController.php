<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\AudioTrack;
use App\Models\SleepWell\HomeItem;
use App\Models\SleepWell\HomeSection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SleepWellTrackController extends Controller
{
    public function index(): View
    {
        $tracks = AudioTrack::query()->latest()->paginate(15);

        return view('admin.sleepwell.tracks.index', [
            'tracks' => $tracks,
        ]);
    }

    public function create(): View
    {
        return view('admin.sleepwell.tracks.form', [
            'track' => new AudioTrack(),
            'method' => 'POST',
            'action' => route('admin.sleepwell.tracks.store'),
            'title' => 'Create SleepWell Track',
            'keyOptions' => $this->keyOptions(),
            'subtitleOptions' => $this->subtitleOptions(),
            'subtitleCatalog' => $this->subtitleCatalog(),
            'subtitleSections' => $this->subtitleSections(),
            'soundTypeOptions' => $this->soundTypeOptions(),
            'sectionOptions' => $this->sectionOptions(),
            'selectedSectionKey' => '',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $payload = $this->validatedPayload($request);

        if (!$request->hasFile('audio_file') && empty($payload['stream_url'])) {
            return back()
                ->withErrors(['stream_url' => 'Provide a stream URL or upload an audio file.'])
                ->withInput();
        }

        $payload['stream_url'] = $this->resolveUrlUpload(
            $request,
            'audio_file',
            (string) ($payload['stream_url'] ?? '')
        );
        $payload['cover_image_url'] = $this->resolveUrlUpload(
            $request,
            'cover_image_file',
            (string) ($payload['cover_image_url'] ?? '')
        );
        unset($payload['audio_file'], $payload['cover_image_file']);

        $homeKey = trim((string) ($payload['key'] ?? ''));
        $sectionKey = trim((string) ($payload['section_key'] ?? ''));
        unset($payload['section_key']);

        $track = AudioTrack::query()->create($payload);
        $this->syncTrackToKeyedHomeItem($track, $homeKey);
        $this->syncTrackToHomeItem($track, $sectionKey);

        return redirect()
            ->route('admin.sleepwell.tracks.index')
            ->with('status', 'Track created successfully.');
    }

    public function edit(AudioTrack $track): View
    {
        return view('admin.sleepwell.tracks.form', [
            'track' => $track,
            'method' => 'PUT',
            'action' => route('admin.sleepwell.tracks.update', $track),
            'title' => 'Edit SleepWell Track',
            'keyOptions' => $this->keyOptions(),
            'subtitleOptions' => $this->subtitleOptions(),
            'subtitleCatalog' => $this->subtitleCatalog(),
            'subtitleSections' => $this->subtitleSections(),
            'soundTypeOptions' => $this->soundTypeOptions(),
            'sectionOptions' => $this->sectionOptions(),
            'selectedSectionKey' => $this->selectedSectionKeyForTrack($track),
        ]);
    }

    public function update(Request $request, AudioTrack $track): RedirectResponse
    {
        $payload = $this->validatedPayload($request);

        $payload['stream_url'] = $this->resolveUrlUpload(
            $request,
            'audio_file',
            (string) ($payload['stream_url'] ?? $track->stream_url)
        );
        $payload['cover_image_url'] = $this->resolveUrlUpload(
            $request,
            'cover_image_file',
            (string) ($payload['cover_image_url'] ?? $track->cover_image_url)
        );
        unset($payload['audio_file'], $payload['cover_image_file']);

        $homeKey = trim((string) ($payload['key'] ?? ''));
        $sectionKey = trim((string) ($payload['section_key'] ?? ''));
        unset($payload['section_key']);

        $track->update($payload);
        $this->syncTrackToKeyedHomeItem($track, $homeKey);
        $this->syncTrackToHomeItem($track, $sectionKey);

        return redirect()
            ->route('admin.sleepwell.tracks.index')
            ->with('status', 'Track updated successfully.');
    }

    public function destroy(AudioTrack $track): RedirectResponse
    {
        $track->delete();

        return redirect()
            ->route('admin.sleepwell.tracks.index')
            ->with('status', 'Track deleted.');
    }

    private function validatedPayload(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'key' => [
                'nullable',
                'string',
                'max:80',
                Rule::exists('sleepwell_home_sections', 'section_key'),
            ],
            'category' => ['required', 'string', 'max:50'],
            'sound_type' => ['nullable', 'string', 'max:50'],
            'section_key' => [
                'nullable',
                'string',
                Rule::exists('sleepwell_home_sections', 'section_key'),
            ],
            'duration_seconds' => ['required', 'integer', 'min:30', 'max:86400'],
            'talking' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'stream_url' => ['nullable', 'url', 'max:500'],
            'cover_image_url' => ['nullable', 'url', 'max:500'],
            'audio_file' => ['nullable', 'file', 'mimes:mp3,wav,m4a,ogg', 'max:51200'],
            'cover_image_file' => ['nullable', 'image', 'max:5120'],
        ]);
    }

    private function resolveUrlUpload(Request $request, string $fileKey, string $fallback): string
    {
        if (!$request->hasFile($fileKey)) {
            return $fallback;
        }

        $path = $request->file($fileKey)->store('sleepwell/uploads', 'public');

        return asset('storage/'.$path);
    }

    /**
     * Pull subtitle presets from existing SleepWell home items.
     *
     * @return array<int, string>
     */
    private function subtitleOptions(): array
    {
        return $this->subtitleCatalog()
            ->pluck('subtitle')
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function subtitleSections(): array
    {
        return $this->subtitleCatalog()
            ->pluck('section_key')
            ->filter(fn ($value) => is_string($value) && trim($value) !== '')
            ->map(fn ($value) => trim((string) $value))
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function soundTypeOptions(): array
    {
        $catalogSoundTypes = $this->subtitleCatalog()
            ->pluck('sound_type')
            ->filter(fn ($value) => is_string($value) && trim($value) !== '')
            ->map(fn ($value) => trim((string) $value))
            ->all();

        $trackSoundTypes = AudioTrack::query()
            ->whereNotNull('sound_type')
            ->where('sound_type', '!=', '')
            ->pluck('sound_type')
            ->map(fn ($value) => trim((string) $value))
            ->all();

        return collect([...$catalogSoundTypes, ...$trackSoundTypes])
            ->filter(fn (string $value) => $value !== '')
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, array{subtitle:string, section_key:string, sound_type:string}>
     */
    private function subtitleCatalog(): Collection
    {
        return HomeItem::query()
            ->with(['section:id,section_key', 'track:id,sound_type'])
            ->whereNotNull('subtitle')
            ->where('subtitle', '!=', '')
            ->get()
            ->map(function (HomeItem $item): array {
                $metaSoundType = is_array($item->meta)
                    ? trim((string) ($item->meta['sound_type'] ?? ''))
                    : '';

                return [
                    'subtitle' => trim((string) $item->subtitle),
                    'section_key' => trim((string) ($item->section?->section_key ?? '')),
                    'sound_type' => trim((string) ($item->track?->sound_type ?? $metaSoundType)),
                ];
            })
            ->filter(fn (array $row) => $row['subtitle'] !== '')
            ->unique(fn (array $row) => implode('|', [$row['subtitle'], $row['section_key'], $row['sound_type']]))
            ->values();
    }

    /**
     * @return array<int, string>
     */
    private function keyOptions(): array
    {
        return HomeSection::query()
            ->whereIn('section_key', $this->homeSectionKeys())
            ->orderBy('sort_order')
            ->pluck('section_key')
            ->map(fn ($value) => trim((string) $value))
            ->filter(fn (string $value) => $value !== '')
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function sectionOptions(): array
    {
        return HomeSection::query()
            ->where('section_key', 'like', 'sounds_%')
            ->orderBy('sort_order')
            ->pluck('section_key')
            ->map(fn ($value) => trim((string) $value))
            ->filter(fn (string $value) => $value !== '')
            ->values()
            ->all();
    }

    private function selectedSectionKeyForTrack(AudioTrack $track): string
    {
        $byTrack = HomeItem::query()
            ->with('section:id,section_key')
            ->where('audio_track_id', $track->id)
            ->first();
        if ($byTrack?->section?->section_key) {
            return (string) $byTrack->section->section_key;
        }

        $byTitle = HomeItem::query()
            ->with('section:id,section_key')
            ->where('title', $track->title)
            ->whereHas('section', fn ($query) => $query->where('section_key', 'like', 'sounds_%'))
            ->first();
        if ($byTitle?->section?->section_key) {
            return (string) $byTitle->section->section_key;
        }

        return '';
    }

    private function syncTrackToKeyedHomeItem(AudioTrack $track, string $key): void
    {
        if ($key === '') {
            return;
        }

        $section = HomeSection::query()
            ->where('section_key', $key)
            ->first();
        if (!$section) {
            return;
        }

        $existing = HomeItem::query()
            ->whereHas('section', function ($query) {
                $query->whereIn('section_key', $this->homeSectionKeys());
            })
            ->where(function ($query) use ($track) {
                $query->where('audio_track_id', $track->id)
                    ->orWhere('title', $track->title);
            })
            ->orderByDesc('audio_track_id')
            ->orderBy('sort_order')
            ->first();

        $sortOrder = $existing?->section_id === $section->id
            ? (int) $existing->sort_order
            : ((int) HomeItem::query()->where('section_id', $section->id)->max('sort_order') + 10);

        $meta = is_array($existing?->meta) ? $existing->meta : [];
        $meta['synced_from_track'] = true;
        $meta['track_id'] = $track->id;
        $meta['track_key'] = $key;
        if (!empty($track->sound_type)) {
            $meta['sound_type'] = $track->sound_type;
        }

        $payload = [
            'section_id' => $section->id,
            'title' => $track->title,
            'subtitle' => $track->subtitle,
            'image_url' => $existing?->image_url ?: $track->cover_image_url,
            'audio_track_id' => $track->id,
            'meta' => $meta,
            'sort_order' => $sortOrder,
            'is_active' => (bool) $track->is_active,
        ];

        if ($existing) {
            $existing->update($payload);
            return;
        }

        HomeItem::query()->create($payload);
    }

    private function syncTrackToHomeItem(AudioTrack $track, string $sectionKey): void
    {
        if ($sectionKey === '') {
            return;
        }

        $section = HomeSection::query()
            ->where('section_key', $sectionKey)
            ->first();
        if (!$section) {
            return;
        }

        $existing = HomeItem::query()
            ->where('section_id', $section->id)
            ->where(function ($query) use ($track) {
                $query->where('audio_track_id', $track->id)
                    ->orWhere('title', $track->title);
            })
            ->orderByDesc('audio_track_id')
            ->orderBy('sort_order')
            ->first();

        $sortOrder = $existing?->sort_order
            ?? ((int) HomeItem::query()->where('section_id', $section->id)->max('sort_order') + 10);
        $meta = is_array($existing?->meta) ? $existing->meta : [];
        if (!empty($track->sound_type)) {
            $meta['sound_type'] = $track->sound_type;
        }

        $payload = [
            'section_id' => $section->id,
            'title' => $track->title,
            'subtitle' => $track->subtitle,
            'audio_track_id' => $track->id,
            'sort_order' => $sortOrder,
            'meta' => $meta,
            'is_active' => (bool) $track->is_active,
        ];

        if ($existing) {
            $existing->update($payload);
            return;
        }

        HomeItem::query()->create($payload);
    }

    /**
     * @return array<int, string>
     */
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
