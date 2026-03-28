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
                    'Fall Asleep Faster',
                    'Sleep All Night',
                    'Relax & Unwind',
                    'Snoring Disruptions',
                    'Manage Tinnitus',
                    'Help My Kids Sleep',
                    'Reduce Anxiety',
                    'Release Stress',
                    'Easier Mornings',
                    'Focus',
                ]],
                'cta_label' => 'Continue',
                'sort_order' => 20,
            ],
            [
                'step_key' => 'age_group',
                'screen_type' => 'single_choice',
                'title' => 'As we age, our sleep needs and challenges change',
                'subtitle' => 'Please select your age group.',
                'options' => ['choices' => ['18 - 24', '25 - 34', '35 - 44', '45 - 54', '55+']],
                'cta_label' => 'Continue',
                'sort_order' => 30,
            ],
            [
                'step_key' => 'gender',
                'screen_type' => 'single_choice',
                'title' => 'Hormone levels can influence sleep patterns',
                'subtitle' => 'Which option best describes you?',
                'options' => ['choices' => ['Female', 'Male', 'Non-binary', 'Other']],
                'cta_label' => 'Continue',
                'sort_order' => 40,
            ],
            [
                'step_key' => 'current_sleep_hours',
                'screen_type' => 'single_choice',
                'title' => 'How many hours of sleep are you currently getting?',
                'subtitle' => null,
                'options' => ['choices' => ['Less than 5 hours', '5 hours', '6 hours', 'More than 7 hours']],
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
                'options' => ['choices' => ['Very Satisfied', 'Neutral', 'Unsatisfied', 'Very unsatisfied']],
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
