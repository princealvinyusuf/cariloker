<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\SavedItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SavedItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $type = $request->query('type');
        $query = SavedItem::query()
            ->where('user_id', $request->user()->id)
            ->latest('updated_at');

        if (is_string($type) && $type !== '') {
            $query->where('item_type', $type);
        }

        return response()->json([
            'items' => $query->get(),
        ]);
    }

    public function upsert(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'item_type' => ['required', 'string', 'max:40'],
            'item_ref' => ['required', 'string', 'max:180'],
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:300'],
            'meta' => ['nullable', 'array'],
            'last_played_at' => ['nullable', 'date'],
        ]);

        $item = SavedItem::query()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'item_type' => $payload['item_type'],
                'item_ref' => $payload['item_ref'],
            ],
            [
                'title' => $payload['title'],
                'subtitle' => $payload['subtitle'] ?? null,
                'meta' => $payload['meta'] ?? [],
                'last_played_at' => $payload['last_played_at'] ?? null,
            ]
        );

        return response()->json(['item' => $item], 201);
    }

    public function destroy(Request $request, SavedItem $savedItem): JsonResponse
    {
        abort_if($savedItem->user_id !== $request->user()->id, 403);
        $savedItem->delete();

        return response()->json(['message' => 'Removed']);
    }
}
