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
use App\Models\BlogPost;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/googlea0b66707fe911939.html', function () {
    return response('google-site-verification: googlea0b66707fe911939.html')
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('google.site-verification');

// Bind job route parameter without the notExpired global scope to allow expired detail page
Route::bind('job', function (string $slug) {
    return \App\Models\Job::withoutGlobalScope('notExpired')
        ->where('slug', $slug)
        ->firstOrFail();
});

// Admin: backend management (auth required)
Route::middleware('auth')->group(function () {
    // Admin Blog Management
    Route::resource('admin/blog', \App\Http\Controllers\Admin\BlogController::class)->names('admin.blog');
    
    // Admin Analytics
    Route::get('/admin/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])
        ->name('admin.analytics.index');
    Route::post('/admin/analytics/clear-relatable-database', [\App\Http\Controllers\Admin\AnalyticsController::class, 'clearRelatableDatabase'])
        ->name('admin.analytics.clear-relatable-database');

    // Admin Job Imports: distribute data from staging table
    Route::get('/admin/jobs/import', [\App\Http\Controllers\Admin\JobImportController::class, 'index'])
        ->name('admin.jobs.import.index');
    Route::post('/admin/jobs/import/start', [\App\Http\Controllers\Admin\JobImportController::class, 'start'])
        ->name('admin.jobs.import.start');
    Route::post('/admin/jobs/import/clean', [\App\Http\Controllers\Admin\JobImportController::class, 'clean'])
        ->name('admin.jobs.import.clean');
    Route::get('/admin/jobs/import/progress', [\App\Http\Controllers\Admin\JobImportController::class, 'progress'])
        ->name('admin.jobs.import.progress');
    // Legacy route from first iteration: keep but redirect to the new page
    Route::post('/admin/jobs/distribute-from-staging', [\App\Http\Controllers\Admin\JobImportController::class, 'distribute'])
        ->name('admin.jobs.distribute-from-staging');
});

Route::get('/locale/{locale}', function (string $locale) {
    if (!in_array($locale, ['id', 'en'])) {
        abort(404);
    }
    session(['locale_v2' => $locale]);
    return back();
})->name('locale.switch');

// Homepage should be served directly on root URL (no redirect).
Route::get('/', [JobController::class, 'beranda'])->name('home');
Route::view('/bedah-cv-gratis', 'cv-reviewer')->name('cv.reviewer');

// Jobs listing page retains the jobs.index name for navigation and filters
Route::get('/jobs', [JobController::class, 'index'])
    ->middleware(['throttle:job-listing'])
    ->name('jobs.index');
Route::get('/lowongan/kategori/{category:slug}', [JobController::class, 'byCategory'])
    ->middleware(['throttle:job-listing'])
    ->name('jobs.by-category');
Route::get('/lowongan/lokasi/{locationSlug}', [JobController::class, 'byLocation'])
    ->middleware(['throttle:job-listing'])
    ->name('jobs.by-location');
Route::get('/lowongan/kategori/{category:slug}/lokasi/{locationSlug}', [JobController::class, 'byCategoryAndLocation'])
    ->middleware(['throttle:job-listing'])
    ->name('jobs.by-category-location');

Route::resource('jobs', JobController::class)->except(['index']);

Route::get('/hello', fn() => 'world');

Route::get('/sitemap.xml', function () {
    $staticUrls = collect([
        route('home'),
        route('cv.reviewer'),
        route('jobs.index'),
        route('companies.index'),
        route('categories.index'),
        route('blog.index'),
        route('about'),
        route('faq'),
        route('privacy-policy'),
        route('terms-of-service'),
        route('cookie-policy'),
    ])->map(fn ($url) => ['loc' => $url, 'lastmod' => now()->toAtomString()]);

    $jobUrls = Job::withoutGlobalScope('notExpired')
        ->where('status', 'published')
        ->latest('updated_at')
        ->limit(5000)
        ->get()
        ->map(fn ($job) => [
            'loc' => route('jobs.show', $job),
            'lastmod' => optional($job->updated_at ?? $job->created_at)->toAtomString(),
        ]);

    $companyUrls = Company::query()
        ->latest('updated_at')
        ->limit(2000)
        ->get()
        ->map(fn ($company) => [
            'loc' => route('companies.show', $company),
            'lastmod' => optional($company->updated_at ?? $company->created_at)->toAtomString(),
        ]);

    $categoryUrls = \App\Models\JobCategory::query()
        ->orderBy('name')
        ->limit(1000)
        ->get()
        ->map(fn ($category) => [
            'loc' => route('jobs.by-category', $category),
            'lastmod' => now()->toAtomString(),
        ]);

    $locationUrls = \App\Models\Location::query()
        ->whereNotNull('city')
        ->whereHas('jobs', fn ($q) => $q->where('status', 'published'))
        ->select(['city', 'updated_at', 'created_at'])
        ->distinct()
        ->orderBy('city')
        ->limit(2000)
        ->get()
        ->map(fn ($location) => [
            'loc' => route('jobs.by-location', ['locationSlug' => \Illuminate\Support\Str::slug((string) $location->city)]),
            'lastmod' => optional($location->updated_at ?? $location->created_at)->toAtomString(),
        ]);

    $categoryLocationUrls = \App\Models\JobCategory::query()
        ->withCount(['jobs' => fn ($q) => $q->where('status', 'published')])
        ->having('jobs_count', '>', 0)
        ->orderByDesc('jobs_count')
        ->limit(8)
        ->get()
        ->flatMap(function ($category) {
            $locations = \App\Models\Location::query()
                ->whereNotNull('city')
                ->whereHas('jobs', function ($q) use ($category) {
                    $q->where('status', 'published')
                        ->where('category_id', $category->id);
                })
                ->withCount(['jobs' => function ($q) use ($category) {
                    $q->where('status', 'published')
                        ->where('category_id', $category->id);
                }])
                ->orderByDesc('jobs_count')
                ->limit(8)
                ->get();

            return $locations->map(fn ($location) => [
                'loc' => route('jobs.by-category-location', [
                    'category' => $category,
                    'locationSlug' => \Illuminate\Support\Str::slug((string) $location->city),
                ]),
                'lastmod' => optional($location->updated_at ?? $location->created_at)->toAtomString(),
            ]);
        })
        ->take(120)
        ->values();

    $blogUrls = BlogPost::query()
        ->where('status', 'published')
        ->whereNotNull('published_at')
        ->latest('published_at')
        ->limit(3000)
        ->get()
        ->map(fn ($post) => [
            'loc' => route('blog.show', $post),
            'lastmod' => optional($post->updated_at ?? $post->published_at)->toAtomString(),
        ]);

    $urls = $staticUrls
        ->merge($jobUrls)
        ->merge($companyUrls)
        ->merge($categoryUrls)
        ->merge($locationUrls)
        ->merge($categoryLocationUrls)
        ->merge($blogUrls)
        ->values();

    return response()
        ->view('sitemap.xml', ['urls' => $urls])
        ->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');

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

Route::get('/beranda', function (Request $request) {
    $query = $request->query();

    if (! empty($query)) {
        return redirect()->route('jobs.index', $query, 301);
    }

    return redirect()->route('home', [], 301);
})->name('beranda');

require __DIR__.'/auth.php';
