<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Location;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Job::query()
            ->with(['company', 'location', 'category'])
            ->where('status', 'published')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . str($request->string('q'))->trim()->toString() . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('title', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhereHas('company', fn ($c) => $c->where('name', 'like', $term));
                });
            })
            ->when($request->filled('category'), fn ($q) => $q->whereHas('category', function ($c) use ($request) {
                $c->where('slug', $request->string('category'));
            }))
            ->when($request->filled('location'), function ($q) use ($request) {
                $term = '%' . str($request->string('location'))->trim()->toString() . '%';
                $q->whereHas('location', function ($l) use ($term) {
                    $l->where('city', 'like', $term)
                      ->orWhere('state', 'like', $term)
                      ->orWhere('country', 'like', $term);
                });
            })
            ->when($request->filled('type'), fn ($q) => $q->where('employment_type', $request->string('type')))
            ->when($request->boolean('remote') || (is_array($request->work_arrangement) && in_array('remote_check', $request->work_arrangement)), fn ($q) => $q->where('is_remote', true))
            ->when($request->filled('min_salary'), fn ($q) => $q->where('salary_min', '>=', (int) $request->integer('min_salary')))
            ->when($request->filled('salary_range'), function ($q) use ($request) {
                $salaryRanges = is_array($request->salary_range) ? $request->salary_range : [$request->salary_range];
                $q->where(function ($sub) use ($salaryRanges) {
                    foreach ($salaryRanges as $range) {
                        switch ($range) {
                            case '0-50000':
                                $sub->orWhere(function ($s) {
                                    $s->where('salary_min', '>=', 0)->where('salary_min', '<=', 50000);
                                });
                                break;
                            case '50000-80000':
                                $sub->orWhere(function ($s) {
                                    $s->where('salary_min', '>=', 50000)->where('salary_min', '<=', 80000);
                                });
                                break;
                            case '80000-100000':
                                $sub->orWhere(function ($s) {
                                    $s->where('salary_min', '>=', 80000)->where('salary_min', '<=', 100000);
                                });
                                break;
                            case '100000-150000':
                                $sub->orWhere(function ($s) {
                                    $s->where('salary_min', '>=', 100000)->where('salary_min', '<=', 150000);
                                });
                                break;
                            case '150000+':
                                $sub->orWhere('salary_min', '>=', 150000);
                                break;
                        }
                    }
                });
            })
            ->when($request->filled('experience'), function ($q) use ($request) {
                $experiences = is_array($request->experience) ? $request->experience : [$request->experience];
                $q->where(function ($sub) use ($experiences) {
                    foreach ($experiences as $exp) {
                        $years = (int) $exp;
                        $sub->orWhere(function ($s) use ($years) {
                            $s->whereNull('experience_min')->orWhere('experience_min', '<=', $years);
                        });
                    }
                });
            })
            ->when($request->filled('date_posted'), function ($q) use ($request) {
                $dateRanges = is_array($request->date_posted) ? $request->date_posted : [$request->date_posted];
                $q->where(function ($sub) use ($dateRanges) {
                    foreach ($dateRanges as $range) {
                        switch ($range) {
                            case 'today':
                                $sub->orWhereDate('created_at', now()->toDateString());
                                break;
                            case 'last_7_days':
                                $sub->orWhere('created_at', '>=', now()->subDays(7));
                                break;
                            case 'last_15_days':
                                $sub->orWhere('created_at', '>=', now()->subDays(15));
                                break;
                            case 'last_month':
                                $sub->orWhere('created_at', '>=', now()->subMonth());
                                break;
                        }
                    }
                });
            })
            ->when($request->filled('work_arrangement'), function ($q) use ($request) {
                $arrangements = is_array($request->work_arrangement) ? $request->work_arrangement : [$request->work_arrangement];
                $q->where(function ($sub) use ($arrangements) {
                    foreach ($arrangements as $arrangement) {
                        if ($arrangement === 'onsite') {
                            $sub->orWhere(function ($s) {
                                $s->where('work_arrangement', 'onsite')->orWhere(function ($s2) {
                                    $s2->whereNull('work_arrangement')->where('is_remote', false);
                                });
                            });
                        } elseif ($arrangement === 'remote') {
                            $sub->orWhere(function ($s) {
                                $s->where('work_arrangement', 'remote')->orWhere('is_remote', true);
                            });
                        }
                    }
                });
            });

        $sort = $request->string('sort', 'date');
        if ($sort === 'salary') {
            $query->orderByDesc('salary_max');
        } else {
            $query->latest();
        }

        $jobs = $query->paginate(10)->withQueryString();

        $categories = JobCategory::query()
            ->withCount(['jobs' => fn ($j) => $j->where('status', 'published')])
            ->orderBy('name')
            ->get();
        
        $popularCompanies = Company::query()
            ->withCount(['jobs' => fn ($j) => $j->where('status', 'published')])
            ->orderByDesc('jobs_count')
            ->limit(8)
            ->get();

        // Check if this is a landing page (no filters applied and not explicitly requesting list view)
        $isLandingPage = !$request->has('list') && !$request->hasAny(['q', 'location', 'type', 'category', 'remote', 'min_salary', 'experience', 'salary_range', 'date_posted', 'work_arrangement', 'sort', 'page']);

        // Get featured jobs for landing page
        $featuredJobs = null;
        if ($isLandingPage) {
            $featuredJobs = Job::query()
                ->with(['company', 'location'])
                ->where('status', 'published')
                ->latest()
                ->limit(6)
                ->get();
        }

        return view('jobs.index', [
            'jobs' => $jobs,
            'categories' => $categories,
            'popularCompanies' => $popularCompanies,
            'isLandingPage' => $isLandingPage,
            'featuredJobs' => $featuredJobs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        $job->load(['company', 'location', 'category', 'skills']);

        $relatedJobs = Job::query()
            ->with(['company', 'location'])
            ->where('status', 'published')
            ->where('id', '!=', $job->id)
            ->where(function ($q) use ($job) {
                $q->where('company_id', $job->company_id);
                if ($job->category_id) {
                    $q->orWhere('category_id', $job->category_id);
                }
            })
            ->latest()
            ->limit(6)
            ->get();

        $totalApplicants = $job->applications()->count();

        return view('jobs.show', [
            'job' => $job,
            'relatedJobs' => $relatedJobs,
            'totalApplicants' => $totalApplicants,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
