<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DistributeJobImports;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
            'errors' => [],
        ]);

        return response()->json($progress);
    }

    protected function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === 'admin', 403);
    }
}


