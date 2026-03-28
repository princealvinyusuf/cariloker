<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\Listener;
use App\Models\SleepWell\OnboardingResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingResponseController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'device_id' => ['required', 'string', 'max:120'],
            'answers' => ['required', 'array'],
        ]);

        $listener = Listener::query()->firstOrCreate(
            ['device_id' => $payload['device_id']],
            ['last_active_at' => now()]
        );

        $response = OnboardingResponse::query()->create([
            'listener_id' => $listener->id,
            'answers' => $payload['answers'],
            'completed_at' => now(),
        ]);

        return response()->json([
            'response_id' => $response->id,
            'message' => 'Onboarding responses saved.',
        ], 201);
    }
}
