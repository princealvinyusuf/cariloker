<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\AdPlacement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdPlacementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $screen = trim((string) $request->query('screen', ''));

        $placements = AdPlacement::query()
            ->where('enabled', true)
            ->when($screen !== '', fn ($q) => $q->where('screen', $screen))
            ->orderByDesc('priority')
            ->get();

        return response()->json([
            'placements' => $placements,
        ]);
    }
}
