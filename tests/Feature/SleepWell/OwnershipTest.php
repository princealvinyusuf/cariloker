<?php

use App\Models\SleepWell\Listener;
use App\Models\SleepWell\MixPreset;
use App\Models\User;

it('prevents users from editing another users mix preset', function () {
    $owner = User::factory()->create();
    $attacker = User::factory()->create();

    $listener = Listener::query()->create([
        'user_id' => $owner->id,
        'device_id' => 'owner-device-001',
        'last_active_at' => now(),
    ]);

    $preset = MixPreset::query()->create([
        'listener_id' => $listener->id,
        'name' => 'Owner Preset',
        'channels' => ['Rain' => 0.7],
    ]);

    $token = $attacker->createToken('test')->plainTextToken;

    $this->withHeader('Authorization', "Bearer {$token}")
        ->putJson("/api/v1/sleepwell/mix-presets/{$preset->id}", [
            'name' => 'Hacked',
        ])
        ->assertForbidden();
});
