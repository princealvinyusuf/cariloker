<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\OnboardingScreen;
use Illuminate\Http\JsonResponse;

class OnboardingContentController extends Controller
{
    public function index(): JsonResponse
    {
        $screens = OnboardingScreen::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'screens' => $screens,
        ]);
    }
}
