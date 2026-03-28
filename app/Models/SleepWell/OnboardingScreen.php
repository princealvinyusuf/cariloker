<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingScreen extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_onboarding_screens';

    protected $fillable = [
        'step_key',
        'screen_type',
        'title',
        'subtitle',
        'options',
        'image_url',
        'cta_label',
        'skippable',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'skippable' => 'boolean',
        'is_active' => 'boolean',
    ];
}
