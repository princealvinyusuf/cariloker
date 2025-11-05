<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index(Request $request)
    {
        $query = Company::query()
            ->with(['location', 'jobs'])
            ->withCount('jobs')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . str($request->string('q'))->trim()->toString() . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('name', 'like', $term)
                        ->orWhere('description', 'like', $term)
                        ->orWhere('industry', 'like', $term);
                });
            })
            ->when($request->filled('location'), function ($q) use ($request) {
                $term = '%' . str($request->string('location'))->trim()->toString() . '%';
                $q->whereHas('location', function ($l) use ($term) {
                    $l->where('city', 'like', $term)
                      ->orWhere('state', 'like', $term)
                      ->orWhere('country', 'like', $term);
                });
            })
            ->when($request->filled('industry'), function ($q) use ($request) {
                $q->where('industry', $request->string('industry'));
            })
            ->when($request->filled('size'), function ($q) use ($request) {
                $q->where('size', $request->string('size'));
            });

        $companies = $query->latest()->paginate(12)->withQueryString();
        
        $locations = \App\Models\Location::orderBy('city')->get();
        $industries = Company::whereNotNull('industry')->distinct()->pluck('industry')->sort()->values();
        $sizes = Company::whereNotNull('size')->distinct()->pluck('size')->sort()->values();

        return view('companies.index', compact('companies', 'locations', 'industries', 'sizes'));
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
    public function show(Company $company)
    {
        $company->load(['jobs' => fn ($q) => $q->where('status', 'published')->latest()]);
        return view('companies.show', ['company' => $company]);
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
