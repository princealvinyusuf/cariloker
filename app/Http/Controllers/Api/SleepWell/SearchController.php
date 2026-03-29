<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\AudioTrack;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));
        $limit = min(30, max(1, (int) $request->query('limit', 12)));

        $tracks = AudioTrack::query()
            ->where('is_active', true)
            ->when($query !== '', function ($builder) use ($query) {
                $builder
                    ->where('title', 'like', '%' . $query . '%')
                    ->orWhere('category', 'like', '%' . $query . '%')
                    ->orWhere('sound_type', 'like', '%' . $query . '%');
            })
            ->orderByDesc('plays_count')
            ->orderBy('title')
            ->limit($limit)
            ->get();

        return response()->json([
            'results' => $tracks,
        ]);
    }
}
