<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\AudioTrack;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = AudioTrack::query()->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', (string) $request->string('category'));
        }

        if ($request->filled('talking')) {
            $query->where('talking', $request->boolean('talking'));
        }

        $tracks = $query->orderBy('category')->orderBy('title')->get();

        return response()->json([
            'categories' => ['whisper', 'no_talking', 'rain', 'roleplay'],
            'tracks' => $tracks,
        ]);
    }
}
