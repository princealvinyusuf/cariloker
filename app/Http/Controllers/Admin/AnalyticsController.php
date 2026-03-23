<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\VisitorIp;
use App\Models\ErrorLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $today = now()->toDateString();

        // Get active jobs (published status and not expired)
        $activeJobsQuery = Job::withoutGlobalScope('notExpired')
            ->where('status', 'published')
            ->where(function ($query) use ($today) {
                $query->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', $today);
            });
        $activeJobs = (clone $activeJobsQuery)->count();
        $activeJobDetails = (clone $activeJobsQuery)
            ->with('company:id,name')
            ->latest('posted_at')
            ->limit(20)
            ->get(['id', 'title', 'company_id', 'posted_at', 'valid_until', 'views', 'apply_clicks']);

        // Get inactive jobs (not published OR expired)
        $inactiveJobsQuery = Job::withoutGlobalScope('notExpired')
            ->where(function ($query) use ($today) {
                $query->where('status', '!=', 'published')
                    ->orWhere(function ($q) use ($today) {
                        $q->where('status', 'published')
                            ->whereNotNull('valid_until')
                            ->whereDate('valid_until', '<', $today);
                    });
            });
        $inactiveJobs = (clone $inactiveJobsQuery)->count();
        $inactiveJobDetails = (clone $inactiveJobsQuery)
            ->with('company:id,name')
            ->latest('updated_at')
            ->limit(20)
            ->get(['id', 'title', 'company_id', 'status', 'valid_until', 'updated_at', 'posted_at']);

        // Count true job categories from the category relation and gather per-category breakdown.
        $categoriesQuery = JobCategory::query()
            ->withCount([
                'jobs as active_jobs_count' => function ($query) use ($today) {
                    $query->withoutGlobalScope('notExpired')
                        ->where('status', 'published')
                        ->where(function ($nestedQuery) use ($today) {
                            $nestedQuery->whereNull('valid_until')
                                ->orWhereDate('valid_until', '>=', $today);
                        });
                },
                'jobs as inactive_jobs_count' => function ($query) use ($today) {
                    $query->withoutGlobalScope('notExpired')
                        ->where(function ($nestedQuery) use ($today) {
                            $nestedQuery->where('status', '!=', 'published')
                                ->orWhere(function ($expiredQuery) use ($today) {
                                    $expiredQuery->where('status', 'published')
                                        ->whereNotNull('valid_until')
                                        ->whereDate('valid_until', '<', $today);
                                });
                        });
                },
            ])
            ->havingRaw('(active_jobs_count + inactive_jobs_count) > 0');
        $totalCategories = (clone $categoriesQuery)->count();
        $categoryDetails = (clone $categoriesQuery)
            ->orderBy('name')
            ->limit(100)
            ->get(['id', 'name', 'slug']);

        // Get total views (sum of all job views) and detailed breakdown
        $totalViews = Job::withoutGlobalScope('notExpired')->sum('views');
        $topViewedJobs = Job::withoutGlobalScope('notExpired')
            ->with('company:id,name')
            ->orderByDesc('views')
            ->limit(20)
            ->get(['id', 'title', 'company_id', 'views', 'apply_clicks', 'posted_at']);

        // Applicants include on-platform applications and external apply clicks
        $totalApplicants = Application::count()
            + Job::withoutGlobalScope('notExpired')->sum('apply_clicks');
        $recentApplications = Application::with(['job:id,title', 'user:id,name,email'])
            ->latest()
            ->limit(20)
            ->get(['id', 'job_listing_id', 'user_id', 'status', 'created_at']);
        $applyClickLeaders = Job::withoutGlobalScope('notExpired')
            ->with('company:id,name')
            ->where('apply_clicks', '>', 0)
            ->orderByDesc('apply_clicks')
            ->limit(20)
            ->get(['id', 'title', 'company_id', 'apply_clicks', 'views']);

        // Get unique IP visitors count
        $uniqueIpVisitors = VisitorIp::count();
        // Get total HTTP error count (status_code >= 400)
        $totalErrors = ErrorLog::where('status_code', '>=', 400)->count();
        $recentVisitors = VisitorIp::orderByDesc('last_visited_at')
            ->limit(20)
            ->get(['id', 'ip_address', 'first_visited_at', 'last_visited_at', 'visit_count']);

        return view('admin.analytics.index', [
            'activeJobs' => $activeJobs,
            'inactiveJobs' => $inactiveJobs,
            'totalCategories' => $totalCategories,
            'totalViews' => $totalViews,
            'totalApplicants' => $totalApplicants,
            'uniqueIpVisitors' => $uniqueIpVisitors,
            'totalErrors' => $totalErrors,
            'activeJobDetails' => $activeJobDetails,
            'inactiveJobDetails' => $inactiveJobDetails,
            'categoryDetails' => $categoryDetails,
            'topViewedJobs' => $topViewedJobs,
            'recentApplications' => $recentApplications,
            'applyClickLeaders' => $applyClickLeaders,
            'recentVisitors' => $recentVisitors,
        ]);
    }

    /**
     * Clear analytics-related datasets and counters.
     */
    public function clearRelatableDatabase(): RedirectResponse
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        DB::transaction(function () {
            VisitorIp::query()->delete();
            ErrorLog::query()->delete();
            Application::query()->delete();
            Job::withoutGlobalScope('notExpired')->update([
                'views' => 0,
                'apply_clicks' => 0,
            ]);
        });

        return redirect()
            ->route('admin.analytics.index')
            ->with('status', __('Relatable analytics data was cleared successfully.'));
    }
}
