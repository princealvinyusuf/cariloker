<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories.
     */
    public function index()
    {
        $categories = JobCategory::query()
            ->withCount(['jobs' => fn ($j) => $j->where('status', 'published')])
            ->orderBy('name')
            ->get();
        
        return view('categories.index', compact('categories'));
    }
}
