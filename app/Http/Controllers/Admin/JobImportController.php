<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DistributeJobImports;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JobImportController extends Controller
{
    /**
     * Legacy redirect from old quick action to the new import page.
     */
    public function distribute(): RedirectResponse
    {
        return redirect()->route('admin.jobs.import.index');
    }

    /**
     * Show the dedicated admin page for distributing data from staging.
     */
    public function index(): \Illuminate\View\View
    {
        $progress = Cache::get(DistributeJobImports::PROGRESS_KEY, [
            'total' => DB::table('job_imports')->count(),
            'processed' => 0,
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
        $total = (int) DB::table('job_imports')->count();

        if ($total === 0) {
            Cache::put(DistributeJobImports::PROGRESS_KEY, [
                'total' => 0,
                'processed' => 0,
                'running' => false,
                'errors' => ['No data found in job_imports staging table.'],
            ], 3600);

            return response()->json([
                'status' => 'error',
                'message' => 'No data found in job_imports staging table.',
            ], 400);
        }

        $existing = Cache::get(DistributeJobImports::PROGRESS_KEY);
        if ($existing && !empty($existing['running'])) {
            return response()->json([
                'status' => 'already_running',
                'message' => 'A distribute job is already running.',
            ]);
        }

        Cache::put(DistributeJobImports::PROGRESS_KEY, [
            'total' => $total,
            'processed' => 0,
            'running' => true,
            'errors' => [],
        ], 3600);

        DistributeJobImports::dispatch();

        return response()->json([
            'status' => 'started',
        ]);
    }

    /**
     * Return current progress as JSON for polling.
     */
    public function progress(): JsonResponse
    {
        $progress = Cache::get(DistributeJobImports::PROGRESS_KEY, [
            'total' => DB::table('job_imports')->count(),
            'processed' => 0,
            'running' => false,
            'errors' => [],
        ]);

        return response()->json($progress);
    }
}


