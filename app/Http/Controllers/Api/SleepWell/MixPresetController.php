<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\Listener;
use App\Models\SleepWell\MixPreset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MixPresetController extends Controller
{
    public function forCurrentUser(Request $request): JsonResponse
    {
        $listener = Listener::query()
            ->where('user_id', $request->user()->id)
            ->latest('last_active_at')
            ->first();

        $presets = $listener
            ? MixPreset::query()->where('listener_id', $listener->id)->latest()->get()
            : collect();

        return response()->json(['presets' => $presets]);
    }

    public function index(string $deviceId): JsonResponse
    {
        $listener = Listener::query()->where('device_id', $deviceId)->first();
        $presets = $listener
            ? MixPreset::query()->where('listener_id', $listener->id)->latest()->get()
            : collect();

        return response()->json(['presets' => $presets]);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'device_id' => ['required', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:80'],
            'channels' => ['required', 'array'],
        ]);

        $listener = Listener::query()->firstOrCreate(
            ['device_id' => $payload['device_id']],
            ['last_active_at' => now()]
        );

        $preset = MixPreset::query()->create([
            'listener_id' => $listener->id,
            'name' => $payload['name'],
            'channels' => $payload['channels'],
        ]);

        return response()->json(['preset' => $preset], 201);
    }

    public function update(Request $request, MixPreset $preset): JsonResponse
    {
        $payload = $request->validate([
            'name' => ['nullable', 'string', 'max:80'],
            'channels' => ['nullable', 'array'],
        ]);

        $listener = $preset->listener;
        if ($request->user() && $listener?->user_id !== $request->user()->id) {
            abort(403);
        }

        $preset->update([
            'name' => $payload['name'] ?? $preset->name,
            'channels' => $payload['channels'] ?? $preset->channels,
        ]);

        return response()->json(['preset' => $preset]);
    }

    public function destroy(Request $request, MixPreset $preset): JsonResponse
    {
        $listener = $preset->listener;
        if ($request->user() && $listener?->user_id !== $request->user()->id) {
            abort(403);
        }

        $preset->delete();

        return response()->json(['message' => 'Preset deleted.']);
    }
}
