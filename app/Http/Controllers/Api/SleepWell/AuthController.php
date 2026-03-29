<?php

namespace App\Http\Controllers\Api\SleepWell;

use App\Http\Controllers\Controller;
use App\Models\SleepWell\Listener;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'device_id' => ['nullable', 'string', 'max:120'],
        ]);

        $user = User::query()->create([
            'name' => $payload['name'],
            'email' => strtolower($payload['email']),
            'password' => $payload['password'],
            'role' => 'candidate',
        ]);

        $token = $user->createToken('sleepwell-mobile')->plainTextToken;
        $this->attachDeviceToUser($user, $payload['device_id'] ?? null);

        return response()->json([
            'token' => $token,
            'user' => $this->serializeUser($user),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'device_id' => ['nullable', 'string', 'max:120'],
        ]);

        $user = User::query()->where('email', strtolower($payload['email']))->first();
        if (!$user || !Hash::check($payload['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('sleepwell-mobile')->plainTextToken;
        $this->attachDeviceToUser($user, $payload['device_id'] ?? null);

        return response()->json([
            'token' => $token,
            'user' => $this->serializeUser($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out']);
    }

    private function attachDeviceToUser(User $user, ?string $deviceId): void
    {
        if (!$deviceId) {
            return;
        }

        Listener::query()->updateOrCreate(
            ['device_id' => $deviceId],
            ['user_id' => $user->id, 'last_active_at' => now()]
        );
    }

    private function serializeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }
}
