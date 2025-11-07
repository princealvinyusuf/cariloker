<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CookiePolicyController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\TermsOfServiceController;
use Illuminate\Support\Facades\Route;

// Bind job route parameter without the notExpired global scope to allow expired detail page
Route::bind('job', function (string $slug) {
    return \App\Models\Job::withoutGlobalScope('notExpired')
        ->where('slug', $slug)
        ->firstOrFail();
});

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
    
    // Admin Blog Management
    Route::resource('admin/blog', \App\Http\Controllers\Admin\BlogController::class)->names('admin.blog');
    
    // Admin Analytics
    Route::get('/admin/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])
        ->name('admin.analytics.index');
});

Route::get('/locale/{locale}', function (string $locale) {
    if (!in_array($locale, ['id', 'en'])) {
        abort(404);
    }
    session(['locale_v2' => $locale]);
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

Route::get('/terms-of-service', [TermsOfServiceController::class, 'index'])->name('terms-of-service');
Route::get('/terms-of-service/edit', [TermsOfServiceController::class, 'edit'])->middleware('auth')->name('terms-of-service.edit');
Route::put('/terms-of-service', [TermsOfServiceController::class, 'update'])->middleware('auth')->name('terms-of-service.update');

Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('privacy-policy');
Route::get('/privacy-policy/edit', [PrivacyPolicyController::class, 'edit'])->middleware('auth')->name('privacy-policy.edit');
Route::put('/privacy-policy', [PrivacyPolicyController::class, 'update'])->middleware('auth')->name('privacy-policy.update');

Route::resource('jobs', JobController::class)->only(['index', 'show']);
Route::get('/jobs/{job}/apply/external', [ApplicationController::class, 'redirectToExternal'])
    ->name('jobs.apply.external');
Route::resource('companies', CompanyController::class)->only(['index', 'show']);
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{blogPost}', [BlogController::class, 'show'])->name('blog.show');

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
