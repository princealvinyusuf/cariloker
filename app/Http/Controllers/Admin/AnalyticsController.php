<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\VisitorIp;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;

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

        // Count unique job titles as fallback "categories" and gather detail breakdown
        $totalCategories = Job::withoutGlobalScope('notExpired')
            ->select('title')
            ->distinct()
            ->count('title');
        $categoryDetails = Job::withoutGlobalScope('notExpired')
            ->select('title')
            ->selectRaw(
                "SUM(CASE WHEN status = 'published' AND (valid_until IS NULL OR DATE(valid_until) >= ?) THEN 1 ELSE 0 END) as active_jobs_count",
                [$today]
            )
            ->selectRaw(
                "SUM(CASE WHEN status != 'published' OR (status = 'published' AND valid_until IS NOT NULL AND DATE(valid_until) < ?) THEN 1 ELSE 0 END) as inactive_jobs_count",
                [$today]
            )
            ->groupBy('title')
            ->orderBy('title')
            ->limit(100)
            ->get();

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
}
