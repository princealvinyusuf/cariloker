<?php

use App\Models\AboutPageContent;
use App\Models\Faq;

it('returns about legal content payload for sleepwell api', function () {
    AboutPageContent::query()->create([
        'key' => 'hero_title',
        'value' => 'About SleepWell',
        'type' => 'text',
        'label' => 'Hero title',
        'section' => 'hero',
        'order' => 1,
    ]);

    AboutPageContent::query()->create([
        'key' => 'about_content',
        'value' => 'SleepWell helps users relax and rest.',
        'type' => 'textarea',
        'label' => 'About',
        'section' => 'about',
        'order' => 2,
    ]);

    $this->getJson('/api/v1/sleepwell/legal/about')
        ->assertOk()
        ->assertJsonPath('slug', 'about')
        ->assertJsonPath('title', 'About SleepWell')
        ->assertJsonStructure([
            'updated_at',
            'blocks' => [
                ['heading', 'body', 'section'],
            ],
        ]);
});

it('returns help content from active faq entries', function () {
    Faq::query()->create([
        'question' => 'How do I reset my account?',
        'answer' => 'Open profile settings and choose reset.',
        'order' => 1,
        'is_active' => true,
    ]);

    Faq::query()->create([
        'question' => 'Inactive question',
        'answer' => 'Should not show.',
        'order' => 2,
        'is_active' => false,
    ]);

    $this->getJson('/api/v1/sleepwell/legal/help')
        ->assertOk()
        ->assertJsonPath('slug', 'help')
        ->assertJsonPath('title', 'Help & Support')
        ->assertJsonCount(1, 'blocks')
        ->assertJsonPath('blocks.0.heading', 'How do I reset my account?');
});
