<?php

use App\Http\Controllers\Api\SleepWell\CatalogController;
use App\Http\Controllers\Api\SleepWell\InsightsController;
use App\Http\Controllers\Api\SleepWell\MixPresetController;
use App\Http\Controllers\Api\SleepWell\OnboardingController;
use App\Http\Controllers\Api\SleepWell\SessionController;
use App\Http\Controllers\Api\SleepWell\SleepNowController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/sleepwell')->group(function () {
    Route::post('/onboarding', [OnboardingController::class, 'store']);
    Route::get('/catalog', [CatalogController::class, 'index']);

    Route::post('/sessions/start', [SessionController::class, 'start']);
    Route::post('/sessions/{session}/event', [SessionController::class, 'event']);
    Route::post('/sessions/{session}/end', [SessionController::class, 'end']);

    Route::post('/sleep-now', [SleepNowController::class, 'start']);
    Route::get('/insights/{deviceId}', [InsightsController::class, 'show']);

    Route::get('/mix-presets/{deviceId}', [MixPresetController::class, 'index']);
    Route::post('/mix-presets', [MixPresetController::class, 'store']);
});
