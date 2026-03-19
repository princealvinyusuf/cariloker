<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DistributeJobImports;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class JobImportController extends Controller
{
    /**
     * Legacy redirect from old quick action to the new import page.
     */
    public function distribute(): RedirectResponse
    {
        $this->authorizeAdmin();
        return redirect()->route('admin.jobs.import.index');
    }

    /**
     * Show the dedicated admin page for distributing data from staging.
     */
    public function index(): \Illuminate\View\View
    {
        $this->authorizeAdmin();
        $progress = Cache::get(DistributeJobImports::PROGRESS_KEY, [
            'total' => DB::table('job_imports')->count(),
            'processed' => 0,
            'succeeded' => 0,
            'failed' => 0,
            'skipped' => 0,
            'running' => false,
            'started_at' => null,
            'elapsed_seconds' => 0,
            'eta_seconds' => null,
            'rows_per_second' => 0,
            'chunk_rows_per_second' => 0,
            'queue_warning' => null,
            'errors' => [],
        ]);
        $progress['queue_warning'] = $this->detectQueueStallWarning($progress);

        return view('admin.jobs.import', [
            'progress' => $progress,
        ]);
    }

    /**
     * Start the distribute job (queued) and initialize progress tracking.
     */
    public function start(Request $request): JsonResponse
    {
        $this->authorizeAdmin();
        $total = (int) DB::table('job_imports')->count();

        if ($total === 0) {
            Cache::put(DistributeJobImports::PROGRESS_KEY, [
                'total' => 0,
                'processed' => 0,
                'succeeded' => 0,
                'failed' => 0,
                'skipped' => 0,
                'running' => false,
                'started_at' => null,
                'elapsed_seconds' => 0,
                'eta_seconds' => null,
                'rows_per_second' => 0,
                'chunk_rows_per_second' => 0,
                'queue_warning' => null,
                'errors' => ['No data found in job_imports staging table.'],
            ], 21600);

            return response()->json([
                'status' => 'error',
                'message' => 'No data found in job_imports staging table.',
            ], 400);
        }

        if (!Cache::add(DistributeJobImports::LOCK_KEY, now()->timestamp, 21600)) {
            return response()->json([
                'status' => 'already_running',
                'message' => 'A distribute job is already running.',
            ]);
        }

        Cache::put(DistributeJobImports::PROGRESS_KEY, [
            'total' => $total,
            'processed' => 0,
            'succeeded' => 0,
            'failed' => 0,
            'skipped' => 0,
            'running' => true,
            'started_at' => now()->timestamp,
            'elapsed_seconds' => 0,
            'eta_seconds' => null,
            'rows_per_second' => 0,
            'chunk_rows_per_second' => 0,
            'queue_warning' => null,
            'errors' => [],
        ], 21600);

        try {
            DistributeJobImports::dispatch();
        } catch (Throwable $e) {
            Cache::put(DistributeJobImports::PROGRESS_KEY, [
                'total' => $total,
                'processed' => 0,
                'succeeded' => 0,
                'failed' => 1,
                'skipped' => 0,
                'running' => false,
                'started_at' => null,
                'elapsed_seconds' => 0,
                'eta_seconds' => null,
                'rows_per_second' => 0,
                'chunk_rows_per_second' => 0,
                'queue_warning' => null,
                'errors' => ['Failed to dispatch import job: ' . $e->getMessage()],
            ], 21600);
            Cache::forget(DistributeJobImports::LOCK_KEY);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to dispatch import job.',
            ], 500);
        }

        return response()->json([
            'status' => 'started',
        ]);
    }

    /**
     * Return current progress as JSON for polling.
     */
    public function progress(): JsonResponse
    {
        $this->authorizeAdmin();
        $progress = Cache::get(DistributeJobImports::PROGRESS_KEY, [
            'total' => DB::table('job_imports')->count(),
            'processed' => 0,
            'succeeded' => 0,
            'failed' => 0,
            'skipped' => 0,
            'running' => false,
            'started_at' => null,
            'elapsed_seconds' => 0,
            'eta_seconds' => null,
            'rows_per_second' => 0,
            'chunk_rows_per_second' => 0,
            'queue_warning' => null,
            'errors' => [],
        ]);
        $progress['queue_warning'] = $this->detectQueueStallWarning($progress);

        return response()->json($progress);
    }

    /**
     * Clean imported relational data while preserving job_imports staging rows.
     */
    public function clean(Request $request): JsonResponse
    {
        $this->authorizeAdmin();
        $mode = $request->input('mode', 'safe');
        if (!in_array($mode, ['safe', 'fast'], true)) {
            $mode = 'safe';
        }

        if (!Cache::add(DistributeJobImports::LOCK_KEY, now()->timestamp, 600)) {
            return response()->json([
                'status' => 'already_running',
                'message' => 'Import is currently running. Please wait until it finishes.',
            ], 409);
        }

        try {
            if ($mode === 'fast') {
                $counts = $this->cleanFastMode();
            } else {
                $counts = $this->cleanSafeMode();
            }

            Cache::put(DistributeJobImports::PROGRESS_KEY, [
                'total' => DB::table('job_imports')->count(),
                'processed' => 0,
                'succeeded' => 0,
                'failed' => 0,
                'skipped' => 0,
                'running' => false,
                'started_at' => null,
                'elapsed_seconds' => 0,
                'eta_seconds' => null,
                'rows_per_second' => 0,
                'chunk_rows_per_second' => 0,
                'queue_warning' => null,
                'errors' => [],
            ], 21600);

            return response()->json([
                'status' => 'cleaned',
                'message' => $mode === 'fast'
                    ? 'Fast cleanup completed. job_imports data remains untouched.'
                    : 'Safe cleanup completed. job_imports data remains untouched.',
                'counts' => $counts,
                'mode' => $mode,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to clean related data: ' . $e->getMessage(),
            ], 500);
        } finally {
            Cache::forget(DistributeJobImports::LOCK_KEY);
        }
    }

    protected function cleanSafeMode(): array
    {
        return DB::transaction(function () {
            $companyIds = DB::table('job_listings')
                ->whereNotNull('company_id')
                ->distinct()
                ->pluck('company_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $categoryIds = DB::table('job_listings')
                ->whereNotNull('category_id')
                ->distinct()
                ->pluck('category_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $locationIds = DB::table('job_listings')
                ->whereNotNull('location_id')
                ->distinct()
                ->pluck('location_id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $jobsDeleted = DB::table('job_listings')->delete();

            $companiesDeleted = $this->deleteOrphanedCompanies($companyIds);
            $categoriesDeleted = $this->deleteOrphanedCategories($categoryIds);
            $locationsDeleted = $this->deleteOrphanedLocations($locationIds);

            return [
                'jobs_deleted' => $jobsDeleted,
                'companies_deleted' => $companiesDeleted,
                'categories_deleted' => $categoriesDeleted,
                'locations_deleted' => $locationsDeleted,
            ];
        });
    }

    protected function cleanFastMode(): array
    {
        $companyIds = DB::table('job_listings')
            ->whereNotNull('company_id')
            ->distinct()
            ->pluck('company_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $categoryIds = DB::table('job_listings')
            ->whereNotNull('category_id')
            ->distinct()
            ->pluck('category_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $locationIds = DB::table('job_listings')
            ->whereNotNull('location_id')
            ->distinct()
            ->pluck('location_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $jobsDeleted = (int) DB::table('job_listings')->count();

        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            try {
                DB::table('job_listing_skill')->truncate();
                DB::table('applications')->truncate();
                DB::table('saved_jobs')->truncate();
                DB::table('job_listings')->truncate();
            } finally {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
        } elseif ($driver === 'pgsql') {
            DB::statement('TRUNCATE TABLE job_listing_skill, applications, saved_jobs, job_listings RESTART IDENTITY CASCADE');
        } else {
            // SQLite/others fallback to bulk delete when TRUNCATE is unavailable.
            DB::table('job_listing_skill')->delete();
            DB::table('applications')->delete();
            DB::table('saved_jobs')->delete();
            DB::table('job_listings')->delete();
        }

        $companiesDeleted = $this->deleteOrphanedCompanies($companyIds);
        $categoriesDeleted = $this->deleteOrphanedCategories($categoryIds);
        $locationsDeleted = $this->deleteOrphanedLocations($locationIds);

        return [
            'jobs_deleted' => $jobsDeleted,
            'companies_deleted' => $companiesDeleted,
            'categories_deleted' => $categoriesDeleted,
            'locations_deleted' => $locationsDeleted,
        ];
    }

    /**
     * @param array<int> $ids
     */
    protected function deleteOrphanedCompanies(array $ids): int
    {
        if ($ids === []) {
            return 0;
        }

        $deleted = 0;
        foreach (array_chunk(array_values(array_unique($ids)), 1000) as $chunk) {
            $deleted += DB::table('companies')
                ->whereIn('id', $chunk)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('job_listings')
                        ->whereColumn('job_listings.company_id', 'companies.id');
                })
                ->delete();
        }

        return $deleted;
    }

    /**
     * @param array<int> $ids
     */
    protected function deleteOrphanedCategories(array $ids): int
    {
        if ($ids === []) {
            return 0;
        }

        $deleted = 0;
        foreach (array_chunk(array_values(array_unique($ids)), 1000) as $chunk) {
            $deleted += DB::table('job_categories')
                ->whereIn('id', $chunk)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('job_listings')
                        ->whereColumn('job_listings.category_id', 'job_categories.id');
                })
                ->delete();
        }

        return $deleted;
    }

    /**
     * @param array<int> $ids
     */
    protected function deleteOrphanedLocations(array $ids): int
    {
        if ($ids === []) {
            return 0;
        }

        $deleted = 0;
        foreach (array_chunk(array_values(array_unique($ids)), 1000) as $chunk) {
            $deleted += DB::table('locations')
                ->whereIn('id', $chunk)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('job_listings')
                        ->whereColumn('job_listings.location_id', 'locations.id');
                })
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('companies')
                        ->whereColumn('companies.location_id', 'locations.id');
                })
                ->delete();
        }

        return $deleted;
    }

    protected function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === 'admin', 403);
    }

    /**
     * @param array<string, mixed> $progress
     */
    protected function detectQueueStallWarning(array $progress): ?string
    {
        if (empty($progress['running'])) {
            return null;
        }

        $startedAt = $progress['started_at'] ?? null;
        if (!is_numeric($startedAt)) {
            return null;
        }

        $elapsed = now()->timestamp - (int) $startedAt;
        if ($elapsed < 90) {
            return null;
        }

        if (!Schema::hasTable('jobs')) {
            return null;
        }

        $pendingImportJob = DB::table('jobs')
            ->whereNull('reserved_at')
            ->where('payload', 'like', '%DistributeJobImports%')
            ->exists();

        $processed = (int) ($progress['processed'] ?? 0);
        if ($processed === 0 && $pendingImportJob) {
            return 'Queue appears stalled: import job is pending and not reserved. Start a queue worker (php artisan queue:work).';
        }

        if ($processed === 0 && $elapsed > 300) {
            return 'Import has no progress for over 5 minutes. Check queue worker and failed_jobs.';
        }

        return null;
    }
}


