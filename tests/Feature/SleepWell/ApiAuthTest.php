<?php

use App\Models\User;

it('registers and returns token for sleepwell api', function () {
    $response = $this->postJson('/api/v1/sleepwell/auth/register', [
        'name' => 'Sleep Tester',
        'email' => 'sleep.tester@example.com',
        'password' => 'secret1234',
        'device_id' => 'device-test-001',
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'token',
            'user' => ['id', 'name', 'email'],
        ]);
});

it('logs in and fetches current account', function () {
    $user = User::factory()->create([
        'email' => 'login.sleep@example.com',
        'password' => 'secret1234',
    ]);

    $login = $this->postJson('/api/v1/sleepwell/auth/login', [
        'email' => $user->email,
        'password' => 'secret1234',
    ]);

    $token = $login->json('token');

    $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/v1/sleepwell/auth/me')
        ->assertOk()
        ->assertJsonPath('user.email', $user->email);
});
