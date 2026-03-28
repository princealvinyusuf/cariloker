<?php

namespace Database\Seeders;

use App\Models\SleepWell\OnboardingScreen;
use Illuminate\Database\Seeder;

class SleepWellOnboardingScreenSeeder extends Seeder
{
    public function run(): void
    {
        $screens = [
            [
                'step_key' => 'welcome',
                'screen_type' => 'welcome',
                'title' => 'Welcome',
                'subtitle' => "Let's begin your journey to peaceful sleep",
                'options' => [],
                'cta_label' => 'Start',
                'sort_order' => 10,
            ],
            [
                'step_key' => 'help_goal',
                'screen_type' => 'multi_choice',
                'title' => 'What can we help you with?',
                'subtitle' => 'We are here for you.',
                'options' => ['choices' => [
                    ['label' => 'Fall Asleep Faster', 'emoji' => '🚀'],
                    ['label' => 'Sleep All Night', 'emoji' => '⏰'],
                    ['label' => 'Relax & Unwind', 'emoji' => '🛌'],
                    ['label' => 'Snoring Disruptions', 'emoji' => '🛏️'],
                    ['label' => 'Manage Tinnitus', 'emoji' => '👂'],
                    ['label' => 'Help My Kids Sleep', 'emoji' => '🧸'],
                    ['label' => 'Reduce Anxiety', 'emoji' => '🪴'],
                    ['label' => 'Release Stress', 'emoji' => '🕊️'],
                    ['label' => 'Easier Mornings', 'emoji' => '🌅'],
                    ['label' => 'Focus', 'emoji' => '🎯'],
                ]],
                'cta_label' => 'Continue',
                'sort_order' => 20,
            ],
            [
                'step_key' => 'age_group',
                'screen_type' => 'single_choice',
                'title' => 'As we age, our sleep needs and challenges change',
                'subtitle' => 'Please select your age group.',
                'options' => ['choices' => [
                    ['label' => '18 - 24'],
                    ['label' => '25 - 34'],
                    ['label' => '35 - 44'],
                    ['label' => '45 - 54'],
                    ['label' => '55+'],
                ]],
                'cta_label' => 'Continue',
                'sort_order' => 30,
            ],
            [
                'step_key' => 'gender',
                'screen_type' => 'single_choice',
                'title' => 'Hormone levels can influence sleep patterns',
                'subtitle' => 'Which option best describes you?',
                'options' => ['choices' => [
                    ['label' => 'Female'],
                    ['label' => 'Male'],
                    ['label' => 'Non-binary'],
                    ['label' => 'Other'],
                ]],
                'cta_label' => 'Continue',
                'sort_order' => 40,
            ],
            [
                'step_key' => 'current_sleep_hours',
                'screen_type' => 'single_choice',
                'title' => 'How many hours of sleep are you currently getting?',
                'subtitle' => null,
                'options' => ['choices' => [
                    ['label' => 'Less than 5 hours', 'emoji' => '🕐'],
                    ['label' => '5 hours', 'emoji' => '🕑'],
                    ['label' => '6 hours', 'emoji' => '🕒'],
                    ['label' => 'More than 7 hours', 'emoji' => '🕗'],
                ]],
                'cta_label' => 'Continue',
                'sort_order' => 50,
            ],
            [
                'step_key' => 'desired_sleep_hours',
                'screen_type' => 'slider',
                'title' => "Set the amount of hours you'd like to spend sleeping.",
                'subtitle' => 'Doctors recommend 7 to 9 hours every night to be healthy.',
                'options' => ['min' => 5, 'max' => 10, 'default' => 8],
                'cta_label' => 'Continue',
                'sort_order' => 60,
            ],
            [
                'step_key' => 'sleep_satisfaction',
                'screen_type' => 'single_choice',
                'title' => 'How satisfied are you with your sleep?',
                'subtitle' => null,
                'options' => ['choices' => [
                    ['label' => 'Very Satisfied', 'emoji' => '😁'],
                    ['label' => 'Neutral', 'emoji' => '😐'],
                    ['label' => 'Unsatisfied', 'emoji' => '🥱'],
                    ['label' => 'Very unsatisfied', 'emoji' => '😔'],
                ]],
                'cta_label' => 'Continue',
                'sort_order' => 70,
            ],
            [
                'step_key' => 'social_proof',
                'screen_type' => 'info',
                'title' => 'Trusted by over 65 million people',
                'subtitle' => 'Preparing your sleep plan',
                'options' => ['stats' => [
                    '91% of listeners sleep better',
                    '4.8 stars from 600k+ reviews',
                    '2+ billion relaxation sessions',
                    '65 times featured in the App Store',
                ]],
                'cta_label' => 'Continue',
                'sort_order' => 80,
            ],
            [
                'step_key' => 'email_capture',
                'screen_type' => 'email',
                'title' => 'Stay Updated on Your Journey to Restful Nights',
                'subtitle' => 'Get weekly progress insights and new content to your inbox.',
                'options' => [],
                'cta_label' => 'Continue',
                'sort_order' => 90,
            ],
        ];

        foreach ($screens as $screen) {
            OnboardingScreen::query()->updateOrCreate(
                ['step_key' => $screen['step_key']],
                [
                    'screen_type' => $screen['screen_type'],
                    'title' => $screen['title'],
                    'subtitle' => $screen['subtitle'],
                    'options' => $screen['options'],
                    'cta_label' => $screen['cta_label'],
                    'skippable' => true,
                    'sort_order' => $screen['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
