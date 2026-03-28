<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\AudioTrack;
use App\Models\SleepWell\Listener;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SleepNowController extends Controller
{
    public function start(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'device_id' => ['required', 'string', 'max:120'],
        ]);

        $listener = Listener::query()
            ->where('device_id', $payload['device_id'])
            ->first();

        $preferredCategories = $listener?->preferred_categories ?? [];
        $prefersTalking = $listener?->prefers_talking;

        $tracksQuery = AudioTrack::query()->where('is_active', true);

        if (!empty($preferredCategories)) {
            $tracksQuery->whereIn('category', $preferredCategories);
        }

        if ($prefersTalking !== null) {
            $tracksQuery->where('talking', $prefersTalking);
        }

        $sequence = $tracksQuery
            ->inRandomOrder()
            ->limit(3)
            ->get();

        if ($sequence->isEmpty()) {
            $sequence = AudioTrack::query()->where('is_active', true)->inRandomOrder()->limit(3)->get();
        }

        return response()->json([
            'sleep_now_sequence' => $sequence,
            'fade_out_seconds' => 45,
            'screen_dim_level' => 0.2,
        ]);
    }
}
