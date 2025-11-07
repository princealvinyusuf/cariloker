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

        // Get active jobs (published status and not expired)
        $activeJobs = Job::withoutGlobalScope('notExpired')
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', now()->toDateString());
            })
            ->count();

        // Get inactive jobs (not published OR expired)
        $inactiveJobs = Job::withoutGlobalScope('notExpired')
            ->where(function ($query) {
                $query->where('status', '!=', 'published')
                    ->orWhere(function ($q) {
                        $q->where('status', 'published')
                            ->whereNotNull('valid_until')
                            ->whereDate('valid_until', '<', now()->toDateString());
                    });
            })
            ->count();

        // Get total categories count
        $totalCategories = JobCategory::count();

        // Get total views (sum of all job views)
        $totalViews = Job::withoutGlobalScope('notExpired')
            ->sum('views');

        $totalApplicants = Application::count()
            + Job::withoutGlobalScope('notExpired')->sum('apply_clicks');

        return view('admin.analytics.index', [
            'activeJobs' => $activeJobs,
            'inactiveJobs' => $inactiveJobs,
            'totalCategories' => $totalCategories,
            'totalViews' => $totalViews,
            'totalApplicants' => $totalApplicants,
        ]);
    }
}
