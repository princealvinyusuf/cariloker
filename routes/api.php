<?php

use App\Http\Controllers\Api\SleepWell\CatalogController;
use App\Http\Controllers\Api\SleepWell\AccountController;
use App\Http\Controllers\Api\SleepWell\AdPlacementController;
use App\Http\Controllers\Api\SleepWell\AuthController;
use App\Http\Controllers\Api\SleepWell\HomeFeedController;
use App\Http\Controllers\Api\SleepWell\InsightsController;
use App\Http\Controllers\Api\SleepWell\LegalContentController;
use App\Http\Controllers\Api\SleepWell\MixPresetController;
use App\Http\Controllers\Api\SleepWell\OnboardingContentController;
use App\Http\Controllers\Api\SleepWell\OnboardingController;
use App\Http\Controllers\Api\SleepWell\OnboardingResponseController;
use App\Http\Controllers\Api\SleepWell\SavedItemController;
use App\Http\Controllers\Api\SleepWell\SearchController;
use App\Http\Controllers\Api\SleepWell\SessionController;
use App\Http\Controllers\Api\SleepWell\SleepNowController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/sleepwell')->group(function () {
    Route::get('/onboarding/content', [OnboardingContentController::class, 'index']);
    Route::post('/onboarding', [OnboardingController::class, 'store']);
    Route::post('/onboarding/responses', [OnboardingResponseController::class, 'store']);
    Route::get('/catalog', [CatalogController::class, 'index']);
    Route::get('/home-feed', [HomeFeedController::class, 'index']);
    Route::get('/ad-placements', [AdPlacementController::class, 'index']);
    Route::get('/search', [SearchController::class, 'index']);
    Route::get('/legal/{slug}', [LegalContentController::class, 'show'])
        ->whereIn('slug', ['about', 'privacy', 'terms', 'help']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::post('/sessions/start', [SessionController::class, 'start']);
    Route::post('/sessions/{session}/event', [SessionController::class, 'event']);
    Route::post('/sessions/{session}/end', [SessionController::class, 'end']);

    Route::post('/sleep-now', [SleepNowController::class, 'start']);
    Route::get('/insights/{deviceId}', [InsightsController::class, 'show']);

    Route::get('/mix-presets/{deviceId}', [MixPresetController::class, 'index']);
    Route::post('/mix-presets', [MixPresetController::class, 'store']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AccountController::class, 'me']);
        Route::put('/auth/me', [AccountController::class, 'update']);
        Route::put('/auth/password', [AccountController::class, 'changePassword']);
        Route::get('/insights', [InsightsController::class, 'forCurrentUser']);
        Route::get('/mix-presets', [MixPresetController::class, 'forCurrentUser']);
        Route::put('/mix-presets/{preset}', [MixPresetController::class, 'update']);
        Route::delete('/mix-presets/{preset}', [MixPresetController::class, 'destroy']);
        Route::get('/saved-items', [SavedItemController::class, 'index']);
        Route::post('/saved-items', [SavedItemController::class, 'upsert']);
        Route::delete('/saved-items/{savedItem}', [SavedItemController::class, 'destroy']);
    });
});
