<?php

namespace Database\Seeders;

use App\Models\SleepWell\HomeItem;
use App\Models\SleepWell\HomeSection;
use Illuminate\Database\Seeder;

class SleepWellHomeFeedSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['section_key' => 'featured_content', 'title' => 'Featured content', 'subtitle' => null, 'section_type' => 'hero_carousel', 'sort_order' => 10],
            ['section_key' => 'explore_grid', 'title' => 'Explore more', 'subtitle' => null, 'section_type' => 'grid', 'sort_order' => 20],
            ['section_key' => 'colored_noises', 'title' => 'Colored Noises', 'subtitle' => 'A rainbow of noises awaits.', 'section_type' => 'chips', 'sort_order' => 30],
            ['section_key' => 'top_rated', 'title' => 'Top 5 rated', 'subtitle' => null, 'section_type' => 'top_ranked', 'sort_order' => 40],
            ['section_key' => 'try_something_else', 'title' => 'Try something else', 'subtitle' => null, 'section_type' => 'horizontal', 'sort_order' => 50],
            ['section_key' => 'curated_playlists', 'title' => 'Our curated playlists', 'subtitle' => 'Soothing music collection designed to help you sleep.', 'section_type' => 'horizontal', 'sort_order' => 60],
            ['section_key' => 'sleep_hypnosis', 'title' => 'Sleep Hypnosis', 'subtitle' => null, 'section_type' => 'horizontal', 'sort_order' => 70],
        ];

        foreach ($sections as $sectionData) {
            HomeSection::query()->updateOrCreate(
                ['section_key' => $sectionData['section_key']],
                [
                    'title' => $sectionData['title'],
                    'subtitle' => $sectionData['subtitle'],
                    'section_type' => $sectionData['section_type'],
                    'sort_order' => $sectionData['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        $itemsBySection = [
            'featured_content' => [
                ['title' => 'Spring Forward', 'subtitle' => 'Let us gently ease your body clock.', 'cta_label' => 'Listen', 'sort_order' => 10, 'meta' => ['accent' => '#4E61D6']],
                ['title' => 'Unwind Tonight', 'subtitle' => 'Drift off with calm evening sessions.', 'cta_label' => 'Listen', 'sort_order' => 20, 'meta' => ['accent' => '#5C5EA8']],
            ],
            'explore_grid' => [
                ['title' => 'Sounds', 'sort_order' => 10, 'meta' => ['emoji' => '🔥']],
                ['title' => 'Mixes', 'sort_order' => 20, 'meta' => ['emoji' => '🎭']],
                ['title' => 'Music', 'sort_order' => 30, 'meta' => ['emoji' => '🎼']],
                ['title' => 'Meditations', 'sort_order' => 40, 'meta' => ['emoji' => '🌅']],
                ['title' => 'SleepTales', 'sort_order' => 50, 'meta' => ['emoji' => '📖']],
                ['title' => 'Favorites', 'sort_order' => 60, 'meta' => ['emoji' => '❤️']],
            ],
            'colored_noises' => [
                ['title' => 'White Noise', 'subtitle' => 'White Noise', 'sort_order' => 10],
                ['title' => 'Green Noise', 'subtitle' => 'Green Noise', 'sort_order' => 20],
                ['title' => 'Deep Brown', 'subtitle' => 'Brown Noise', 'sort_order' => 30],
                ['title' => 'Violet Noise', 'subtitle' => 'Violet Noise', 'sort_order' => 40],
            ],
            'top_rated' => [
                ['title' => 'Green Noise Deep Sleep Hypnosis', 'subtitle' => 'Meditation', 'sort_order' => 10, 'meta' => ['rank' => 1]],
                ['title' => "Rosemary's Quilt of Memories", 'subtitle' => 'SleepTale', 'sort_order' => 20, 'meta' => ['rank' => 2]],
                ['title' => 'Bedtime Bliss Sleep Hypnosis', 'subtitle' => 'Meditation', 'sort_order' => 30, 'meta' => ['rank' => 3]],
                ['title' => 'The Legend of the Lunar Calendar', 'subtitle' => 'SleepTale', 'sort_order' => 40, 'meta' => ['rank' => 4]],
                ['title' => 'Delta Sleep Hypnosis', 'subtitle' => 'Meditation', 'sort_order' => 50, 'meta' => ['rank' => 5]],
            ],
            'try_something_else' => [
                ['title' => 'Midnight Photograph', 'subtitle' => 'Music', 'sort_order' => 10],
                ['title' => 'Under the Beardy', 'subtitle' => 'SleepTale • 25 min', 'sort_order' => 20],
                ['title' => 'New Year Resolution', 'subtitle' => 'Meditation', 'sort_order' => 30],
            ],
            'curated_playlists' => [
                ['title' => 'Fall Asleep Fast', 'subtitle' => 'Playlist • 6 h 19 min', 'sort_order' => 10],
                ['title' => 'Deep Lucid Dreaming', 'subtitle' => 'Playlist • 53 min', 'sort_order' => 20],
                ['title' => 'Lofi Cafe', 'subtitle' => 'Playlist • 28 min', 'sort_order' => 30],
            ],
            'sleep_hypnosis' => [
                ['title' => 'Ocean Waves Sleep Hypnosis', 'subtitle' => 'Hypnosis • 26 min', 'sort_order' => 10],
                ['title' => 'Green Noise Deep Sleep Hypnosis', 'subtitle' => 'Hypnosis • 18 min', 'sort_order' => 20],
                ['title' => 'Sound Mind Brown Noise', 'subtitle' => 'Hypnosis • 21 min', 'sort_order' => 30],
            ],
        ];

        foreach ($itemsBySection as $sectionKey => $items) {
            $section = HomeSection::query()->where('section_key', $sectionKey)->first();
            if (!$section) {
                continue;
            }

            foreach ($items as $item) {
                HomeItem::query()->updateOrCreate(
                    [
                        'section_id' => $section->id,
                        'title' => $item['title'],
                    ],
                    [
                        'subtitle' => $item['subtitle'] ?? null,
                        'tag' => $item['tag'] ?? null,
                        'cta_label' => $item['cta_label'] ?? null,
                        'meta' => $item['meta'] ?? [],
                        'sort_order' => $item['sort_order'] ?? 0,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
