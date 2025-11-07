<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\JobCategory;
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

        // Count total categories and gather detail breakdown
        $totalCategories = JobCategory::count();
        $categoryDetails = JobCategory::query()
            ->withCount([
                'jobs as active_jobs_count' => function ($query) use ($today) {
                    $query->withoutGlobalScope('notExpired')
                        ->where('status', 'published')
                        ->where(function ($inner) use ($today) {
                            $inner->whereNull('valid_until')
                                ->orWhereDate('valid_until', '>=', $today);
                        });
                },
                'jobs as inactive_jobs_count' => function ($query) use ($today) {
                    $query->withoutGlobalScope('notExpired')
                        ->where(function ($inner) use ($today) {
                            $inner->where('status', '!=', 'published')
                                ->orWhere(function ($q) use ($today) {
                                    $q->where('status', 'published')
                                        ->whereNotNull('valid_until')
                                        ->whereDate('valid_until', '<', $today);
                                });
                        });
                },
            ])
            ->orderBy('name')
            ->get(['id', 'name']);

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

        return view('admin.analytics.index', [
            'activeJobs' => $activeJobs,
            'inactiveJobs' => $inactiveJobs,
            'totalCategories' => $totalCategories,
            'totalViews' => $totalViews,
            'totalApplicants' => $totalApplicants,
            'activeJobDetails' => $activeJobDetails,
            'inactiveJobDetails' => $inactiveJobDetails,
            'categoryDetails' => $categoryDetails,
            'topViewedJobs' => $topViewedJobs,
            'recentApplications' => $recentApplications,
            'applyClickLeaders' => $applyClickLeaders,
        ]);
    }
}
