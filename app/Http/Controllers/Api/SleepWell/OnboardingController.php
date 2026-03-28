<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\Listener;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'device_id' => ['required', 'string', 'max:120'],
            'timezone' => ['nullable', 'string', 'max:80'],
            'sleep_difficulty' => ['nullable', 'integer', 'min:1', 'max:5'],
            'prefers_talking' => ['nullable', 'boolean'],
            'preferred_categories' => ['nullable', 'array'],
            'preferred_categories.*' => ['string', 'max:50'],
            'preferred_sound_types' => ['nullable', 'array'],
            'preferred_sound_types.*' => ['string', 'max:50'],
        ]);

        $listener = Listener::query()->updateOrCreate(
            ['device_id' => $payload['device_id']],
            [
                'timezone' => $payload['timezone'] ?? null,
                'sleep_difficulty' => $payload['sleep_difficulty'] ?? null,
                'prefers_talking' => $payload['prefers_talking'] ?? null,
                'preferred_categories' => $payload['preferred_categories'] ?? [],
                'preferred_sound_types' => $payload['preferred_sound_types'] ?? [],
                'last_active_at' => now(),
            ]
        );

        return response()->json([
            'listener_id' => $listener->id,
            'message' => 'Onboarding preferences saved.',
        ]);
    }
}
