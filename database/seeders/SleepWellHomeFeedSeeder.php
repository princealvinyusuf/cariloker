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
            ['section_key' => 'promo_therapy', 'title' => 'Still waking up tired?', 'subtitle' => null, 'section_type' => 'promo', 'sort_order' => 25],
            ['section_key' => 'sleep_recorder', 'title' => 'Sleep Recorder', 'subtitle' => 'Monitor and improve', 'section_type' => 'promo', 'sort_order' => 27],
            ['section_key' => 'colored_noises', 'title' => 'Colored Noises', 'subtitle' => 'A rainbow of noises awaits.', 'section_type' => 'chips', 'sort_order' => 30],
            ['section_key' => 'top_rated', 'title' => 'Top 5 rated', 'subtitle' => null, 'section_type' => 'top_ranked', 'sort_order' => 40],
            ['section_key' => 'quick_topics', 'title' => null, 'subtitle' => null, 'section_type' => 'chips', 'sort_order' => 45],
            ['section_key' => 'discover_banner', 'title' => null, 'subtitle' => null, 'section_type' => 'promo', 'sort_order' => 47],
            ['section_key' => 'try_something_else', 'title' => 'Try something else', 'subtitle' => null, 'section_type' => 'horizontal', 'sort_order' => 50],
            ['section_key' => 'curated_playlists', 'title' => 'Our curated playlists', 'subtitle' => 'Soothing music collection designed to help you sleep.', 'section_type' => 'horizontal', 'sort_order' => 60],
            ['section_key' => 'sleep_hypnosis', 'title' => 'Sleep Hypnosis', 'subtitle' => null, 'section_type' => 'horizontal', 'sort_order' => 70],
            ['section_key' => 'sounds_featured', 'title' => 'Sounds', 'subtitle' => null, 'section_type' => 'hero_carousel', 'sort_order' => 80],
            ['section_key' => 'sounds_my_sounds', 'title' => 'My Sounds', 'subtitle' => null, 'section_type' => 'grid', 'sort_order' => 81],
            ['section_key' => 'sounds_popular', 'title' => 'Popular', 'subtitle' => null, 'section_type' => 'grid', 'sort_order' => 82],
            ['section_key' => 'music_hero', 'title' => 'Music', 'subtitle' => null, 'section_type' => 'hero_carousel', 'sort_order' => 90],
            ['section_key' => 'music_top10', 'title' => 'Top 10', 'subtitle' => 'Enjoy music picked for you by DJ BetterSleep.', 'section_type' => 'horizontal', 'sort_order' => 91],
            ['section_key' => 'music_layers', 'title' => 'Music Layers', 'subtitle' => 'Create your own bedtime soundtrack with evolving music loops.', 'section_type' => 'chips', 'sort_order' => 92],
            ['section_key' => 'mixes_favorites', 'title' => 'My Favorites Mixes', 'subtitle' => 'Unwind with your own personal selection of favorite sounds.', 'section_type' => 'horizontal', 'sort_order' => 100],
            ['section_key' => 'mixes_featured', 'title' => 'Spring Forward', 'subtitle' => 'Let us gently ease you into the time change.', 'section_type' => 'promo', 'sort_order' => 101],
            ['section_key' => 'mixes_sound_escapes', 'title' => 'Sound Escapes', 'subtitle' => 'Leave stress behind and sink into rich soundscapes.', 'section_type' => 'horizontal', 'sort_order' => 102],
            ['section_key' => 'meditation_promoted', 'title' => 'Promoted content', 'subtitle' => null, 'section_type' => 'hero_carousel', 'sort_order' => 110],
            ['section_key' => 'meditation_bedtime', 'title' => 'Your bedtime wind-downs', 'subtitle' => 'Let go of the day and ease into sleep.', 'section_type' => 'horizontal', 'sort_order' => 111],
            ['section_key' => 'meditation_new', 'title' => 'New releases & popular guidances', 'subtitle' => 'Try new guidances and all-time favorites.', 'section_type' => 'horizontal', 'sort_order' => 112],
            ['section_key' => 'sleeptales_promoted', 'title' => 'Promoted content', 'subtitle' => null, 'section_type' => 'hero_carousel', 'sort_order' => 120],
            ['section_key' => 'sleeptales_popular', 'title' => 'Popular SleepTales', 'subtitle' => null, 'section_type' => 'horizontal', 'sort_order' => 121],
            ['section_key' => 'sleeptales_cozy', 'title' => 'Get cozy with easy listens', 'subtitle' => null, 'section_type' => 'horizontal', 'sort_order' => 122],
            ['section_key' => 'routine_habits', 'title' => 'HABITS', 'subtitle' => null, 'section_type' => 'promo', 'sort_order' => 130],
            ['section_key' => 'routine_wind_down', 'title' => 'WIND DOWN', 'subtitle' => null, 'section_type' => 'horizontal', 'sort_order' => 131],
            ['section_key' => 'routine_sleep', 'title' => 'SLEEP', 'subtitle' => null, 'section_type' => 'horizontal', 'sort_order' => 132],
            ['section_key' => 'routine_recommendation', 'title' => null, 'subtitle' => null, 'section_type' => 'promo', 'sort_order' => 133],
            ['section_key' => 'insight_sleep_quality', 'title' => null, 'subtitle' => null, 'section_type' => 'promo', 'sort_order' => 140],
            ['section_key' => 'insight_snore', 'title' => null, 'subtitle' => null, 'section_type' => 'promo', 'sort_order' => 141],
            ['section_key' => 'insight_phases', 'title' => null, 'subtitle' => null, 'section_type' => 'promo', 'sort_order' => 142],
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
            'promo_therapy' => [
                [
                    'title' => 'You have 50% off your first month of therapy',
                    'subtitle' => 'Take the assessment',
                    'cta_label' => 'Take the assessment',
                    'sort_order' => 10,
                    'meta' => ['badge' => 'betterhelp'],
                ],
            ],
            'sleep_recorder' => [
                [
                    'title' => 'Monitor and improve',
                    'subtitle' => 'Consistency is the best way to improve sleep',
                    'cta_label' => 'Start Recorder',
                    'sort_order' => 10,
                    'meta' => ['quality' => 62],
                ],
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
            'quick_topics' => [
                ['title' => 'Sleep Faster', 'sort_order' => 10, 'meta' => ['emoji' => '🏁']],
                ['title' => 'Hypnosis', 'sort_order' => 20, 'meta' => ['emoji' => '🌀']],
                ['title' => 'Napping', 'sort_order' => 30, 'meta' => ['emoji' => '😴']],
                ['title' => 'Kids', 'sort_order' => 40, 'meta' => ['emoji' => '👶']],
                ['title' => 'Stress', 'sort_order' => 50, 'meta' => ['emoji' => '😣']],
                ['title' => 'Tinnitus', 'sort_order' => 60, 'meta' => ['emoji' => '👂']],
            ],
            'discover_banner' => [
                ['title' => 'DISCOVER', 'subtitle' => 'Find sounds for tonight', 'sort_order' => 10],
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
            'sounds_featured' => [
                ['title' => 'Soothing Colored Noise', 'subtitle' => 'Starter Mix', 'sort_order' => 10, 'meta' => ['emoji' => '🎧']],
                ['title' => 'Rain + Piano', 'subtitle' => 'Sleep in 10 minutes', 'sort_order' => 20, 'meta' => ['emoji' => '🌧️']],
            ],
            'sounds_my_sounds' => [
                ['title' => 'Eternity', 'subtitle' => 'Sound', 'sort_order' => 10],
                ['title' => 'Ocean', 'subtitle' => 'Sound', 'sort_order' => 20],
                ['title' => 'Birds', 'subtitle' => 'Sound', 'sort_order' => 30],
                ['title' => 'River', 'subtitle' => 'Sound', 'sort_order' => 40],
            ],
            'sounds_popular' => [
                ['title' => 'Night', 'subtitle' => 'Sound', 'sort_order' => 10],
                ['title' => 'Campfire', 'subtitle' => 'Sound', 'sort_order' => 20],
                ['title' => 'White Noise', 'subtitle' => 'Sound', 'sort_order' => 30],
                ['title' => 'Brown Noise', 'subtitle' => 'Sound', 'sort_order' => 40],
                ['title' => 'Urban Rain', 'subtitle' => 'Sound', 'sort_order' => 50],
                ['title' => 'Wind Chimes', 'subtitle' => 'Sound', 'sort_order' => 60],
            ],
            'music_hero' => [
                ['title' => 'Peaceful Unwind', 'subtitle' => 'Find stillness and relax to alpha brainwave music.', 'cta_label' => 'Listen', 'sort_order' => 10],
                ['title' => 'Night Drift', 'subtitle' => 'Calm instrumentals for deep rest.', 'cta_label' => 'Listen', 'sort_order' => 20],
            ],
            'music_top10' => [
                ['title' => 'Playlist: Classical Music', 'subtitle' => 'Playlist • 1 h 33 min', 'sort_order' => 10],
                ['title' => 'Clarity and Alertness', 'subtitle' => 'Music', 'sort_order' => 20],
                ['title' => 'Spa Music Collection', 'subtitle' => 'Music', 'sort_order' => 30],
            ],
            'music_layers' => [
                ['title' => 'Droning Bass', 'subtitle' => 'Music Layers', 'sort_order' => 10],
                ['title' => 'Pulsing Bass', 'subtitle' => 'Music Layers', 'sort_order' => 20],
                ['title' => 'Echoing Harmony', 'subtitle' => 'Music Layers', 'sort_order' => 30],
                ['title' => 'Extending Pad', 'subtitle' => 'Music Layers', 'sort_order' => 40],
            ],
            'mixes_favorites' => [
                ['title' => 'Your First Mix', 'subtitle' => 'Mix', 'sort_order' => 10],
                ['title' => 'Create a mix', 'subtitle' => 'Mix', 'sort_order' => 20],
            ],
            'mixes_featured' => [
                ['title' => 'Spring Forward', 'subtitle' => 'Let us gently ease your body into easy rest', 'cta_label' => 'Listen', 'sort_order' => 10],
            ],
            'mixes_sound_escapes' => [
                ['title' => 'Dusk in the Amazon Jungle', 'subtitle' => 'Mix', 'sort_order' => 10],
                ['title' => 'Rainy Day at Lake Titicaca', 'subtitle' => 'Mix', 'sort_order' => 20],
                ['title' => 'Deep Green Dreams', 'subtitle' => 'Mix', 'sort_order' => 30],
            ],
            'meditation_promoted' => [
                ['title' => 'Discover Meditation: 1 Minute Guide To Your Relaxation Tool', 'subtitle' => 'Video • by Andrew Green', 'sort_order' => 10],
            ],
            'meditation_bedtime' => [
                ['title' => 'Starlight Bedtime Hypnosis', 'subtitle' => 'Meditation • 35 min', 'sort_order' => 10],
                ['title' => 'Bedtime Bliss Sleep Hypnosis', 'subtitle' => 'Meditation • 1 h 28 min', 'sort_order' => 20],
            ],
            'meditation_new' => [
                ['title' => 'Green Noise Deep Sleep Hypnosis', 'subtitle' => 'Meditation', 'sort_order' => 10],
                ['title' => 'Back to Sleep Hypnosis', 'subtitle' => 'Meditation', 'sort_order' => 20],
            ],
            'sleeptales_promoted' => [
                ['title' => 'Discover SleepTales: 1 Minute Guide to Your Bedtime Tool', 'subtitle' => 'Video • by Shogo Miyakita', 'sort_order' => 10],
            ],
            'sleeptales_popular' => [
                ['title' => 'The Wonderful Wizard of Oz, Part 1', 'subtitle' => 'SleepTale • 52 min', 'sort_order' => 10],
                ['title' => 'The Underwater City', 'subtitle' => 'SleepTale • 49 min', 'sort_order' => 20],
            ],
            'sleeptales_cozy' => [
                ['title' => '2 a.m. at the Blueberry Hill Diner', 'subtitle' => 'SleepTale • 26 min', 'sort_order' => 10],
                ['title' => 'Camping at Moonlit Lake', 'subtitle' => 'SleepTale • 28 min', 'sort_order' => 20],
                ['title' => 'Wind down in nature', 'subtitle' => 'SleepTale • 27 min', 'sort_order' => 30],
            ],
            'routine_habits' => [
                ['title' => "You don't have any habits. Tap the plus to add one.", 'subtitle' => null, 'sort_order' => 10],
            ],
            'routine_wind_down' => [
                ['title' => 'Dropping into the Present Moment', 'subtitle' => 'Meditation • 14 min', 'sort_order' => 10],
            ],
            'routine_sleep' => [
                ['title' => 'Track your sleep', 'subtitle' => 'Tap to learn more info.', 'sort_order' => 10, 'meta' => ['toggle' => true]],
                ['title' => 'Night Wind', 'subtitle' => 'Mix • 1 h 0 min', 'sort_order' => 20],
            ],
            'routine_recommendation' => [
                ['title' => 'Want to try a different routine?', 'subtitle' => 'Select a new routine and customize it to suit your sleep needs', 'cta_label' => 'Explore more', 'sort_order' => 10],
            ],
            'insight_sleep_quality' => [
                [
                    'title' => 'No Sleep Quality Yet',
                    'subtitle' => 'Track your sleep tonight and wake up to detailed insights here.',
                    'sort_order' => 10,
                    'meta' => ['score' => 0],
                ],
            ],
            'insight_snore' => [
                [
                    'title' => 'Do you snore?',
                    'subtitle' => "Record your sleep sounds to uncover what's disturbing your rest.",
                    'cta_label' => 'Track my sleep',
                    'sort_order' => 10,
                ],
            ],
            'insight_phases' => [
                [
                    'title' => 'Your Sleep Phases',
                    'subtitle' => 'Learn more about your sleeping patterns and how to improve them.',
                    'cta_label' => 'Learn more',
                    'sort_order' => 10,
                ],
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
