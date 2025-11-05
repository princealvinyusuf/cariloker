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
            ->when($request->boolean('remote'), fn ($q) => $q->where('is_remote', true))
            ->when($request->filled('min_salary'), fn ($q) => $q->where('salary_min', '>=', (int) $request->integer('min_salary')))
            ->when($request->filled('experience'), function ($q) use ($request) {
                $years = (int) $request->integer('experience');
                $q->where(function ($sub) use ($years) {
                    $sub->whereNull('experience_min')->orWhere('experience_min', '<=', $years);
                });
            });

        $sort = $request->string('sort', 'date');
        if ($sort === 'salary') {
            $query->orderByDesc('salary_max');
        } else {
            $query->latest();
        }

        $jobs = $query->paginate(10)->withQueryString();

        $categories = JobCategory::query()->orderBy('name')->get();
        $popularCompanies = Company::query()
            ->withCount(['jobs' => fn ($j) => $j->where('status', 'published')])
            ->orderByDesc('jobs_count')
            ->limit(8)
            ->get();

        return view('jobs.index', [
            'jobs' => $jobs,
            'categories' => $categories,
            'popularCompanies' => $popularCompanies,
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
