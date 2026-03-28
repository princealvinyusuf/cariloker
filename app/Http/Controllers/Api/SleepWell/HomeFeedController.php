<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\HomeSection;
use Illuminate\Http\JsonResponse;

class HomeFeedController extends Controller
{
    public function index(): JsonResponse
    {
        $sections = HomeSection::query()
            ->where('is_active', true)
            ->with(['items' => fn ($q) => $q
                ->where('is_active', true)
                ->orderBy('sort_order')
            ])
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'sections' => $sections,
        ]);
    }
}
