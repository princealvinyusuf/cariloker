<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CookiePolicyController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavedJobController;
use Illuminate\Support\Facades\Route;

// Admin: Bulk job import (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/admin/jobs/import', [\App\Http\Controllers\Admin\JobImportController::class, 'create'])
        ->name('admin.jobs.import.create');
    Route::post('/admin/jobs/import', [\App\Http\Controllers\Admin\JobImportController::class, 'store'])
        ->name('admin.jobs.import.store');
    Route::post('/admin/jobs/import/process', [\App\Http\Controllers\Admin\JobImportController::class, 'processStaging'])
        ->name('admin.jobs.import.process');
    Route::post('/admin/jobs/truncate', [\App\Http\Controllers\Admin\JobImportController::class, 'truncateAll'])
        ->name('admin.jobs.truncate');
});

Route::get('/locale/{locale}', function (string $locale) {
    if (!in_array($locale, ['id', 'en'])) {
        abort(404);
    }
    session(['locale' => $locale]);
    return back();
})->name('locale.switch');

Route::get('/', [JobController::class, 'index'])->name('jobs.index');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/about/edit', [AboutController::class, 'edit'])->middleware('auth')->name('about.edit');
Route::put('/about', [AboutController::class, 'update'])->middleware('auth')->name('about.update');

Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/faq/edit', [FaqController::class, 'edit'])->middleware('auth')->name('faq.edit');
Route::put('/faq', [FaqController::class, 'update'])->middleware('auth')->name('faq.update');
Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->middleware('auth')->name('faq.destroy');

Route::get('/cookie-policy', [CookiePolicyController::class, 'index'])->name('cookie-policy');
Route::get('/cookie-policy/edit', [CookiePolicyController::class, 'edit'])->middleware('auth')->name('cookie-policy.edit');
Route::put('/cookie-policy', [CookiePolicyController::class, 'update'])->middleware('auth')->name('cookie-policy.update');

Route::resource('jobs', JobController::class)->only(['index', 'show']);
Route::resource('companies', CompanyController::class)->only(['show']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('jobs.apply');
    Route::post('/jobs/{job}/save', [SavedJobController::class, 'store'])->name('jobs.save');
    Route::delete('/jobs/{job}/save', [SavedJobController::class, 'destroy'])->name('jobs.unsave');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
