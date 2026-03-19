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
            'elapsed_seconds' => 0,
            'eta_seconds' => null,
            'rows_per_second' => 0,
            'chunk_rows_per_second' => 0,
            'errors' => [],
        ]);

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
                'elapsed_seconds' => 0,
                'eta_seconds' => null,
                'rows_per_second' => 0,
                'chunk_rows_per_second' => 0,
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
            'elapsed_seconds' => 0,
            'eta_seconds' => null,
            'rows_per_second' => 0,
            'chunk_rows_per_second' => 0,
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
                'elapsed_seconds' => 0,
                'eta_seconds' => null,
                'rows_per_second' => 0,
                'chunk_rows_per_second' => 0,
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
            'elapsed_seconds' => 0,
            'eta_seconds' => null,
            'rows_per_second' => 0,
            'chunk_rows_per_second' => 0,
            'errors' => [],
        ]);

        return response()->json($progress);
    }

    /**
     * Clean imported relational data while preserving job_imports staging rows.
     */
    public function clean(Request $request): JsonResponse
    {
        $this->authorizeAdmin();

        if (!Schema::hasColumn('job_listings', 'source_hash')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing source_hash column. Please run migrations first.',
            ], 422);
        }

        if (!Cache::add(DistributeJobImports::LOCK_KEY, now()->timestamp, 600)) {
            return response()->json([
                'status' => 'already_running',
                'message' => 'Import is currently running. Please wait until it finishes.',
            ], 409);
        }

        try {
            $counts = DB::transaction(function () {
                $companyIds = DB::table('job_listings')
                    ->whereNotNull('source_hash')
                    ->whereNotNull('company_id')
                    ->distinct()
                    ->pluck('company_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                $categoryIds = DB::table('job_listings')
                    ->whereNotNull('source_hash')
                    ->whereNotNull('category_id')
                    ->distinct()
                    ->pluck('category_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                $locationIds = DB::table('job_listings')
                    ->whereNotNull('source_hash')
                    ->whereNotNull('location_id')
                    ->distinct()
                    ->pluck('location_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();

                $jobsDeleted = DB::table('job_listings')
                    ->whereNotNull('source_hash')
                    ->delete();

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

            Cache::put(DistributeJobImports::PROGRESS_KEY, [
                'total' => DB::table('job_imports')->count(),
                'processed' => 0,
                'succeeded' => 0,
                'failed' => 0,
                'skipped' => 0,
                'running' => false,
                'elapsed_seconds' => 0,
                'eta_seconds' => null,
                'rows_per_second' => 0,
                'chunk_rows_per_second' => 0,
                'errors' => [],
            ], 21600);

            return response()->json([
                'status' => 'cleaned',
                'message' => 'Related imported data has been cleaned. job_imports data remains untouched.',
                'counts' => $counts,
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
}


