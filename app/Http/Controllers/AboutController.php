<?php

namespace App\Http\Controllers;

use App\Models\AboutPageContent;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the about page
     */
    public function index()
    {
        $contents = AboutPageContent::orderBy('section')->orderBy('order')->get()->keyBy('key');
        
        // If no content exists, seed default content
        if ($contents->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'AboutPageContentSeeder']);
            $contents = AboutPageContent::orderBy('section')->orderBy('order')->get()->keyBy('key');
        }
        
        return view('about.index', compact('contents'));
    }

    /**
     * Show the form for editing the about page content
     */
    public function edit()
    {
        // Only allow admin users or authenticated users for now
        if (!auth()->check()) {
            abort(403);
        }
        
        $contents = AboutPageContent::orderBy('section')->orderBy('order')->get();
        
        // If no content exists, seed default content
        if ($contents->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'AboutPageContentSeeder']);
            $contents = AboutPageContent::orderBy('section')->orderBy('order')->get();
        }
        
        $sections = $contents->groupBy('section');
        
        return view('about.edit', compact('sections', 'contents'));
    }

    /**
     * Update the about page content
     */
    public function update(Request $request)
    {
        // Only allow admin users or authenticated users for now
        if (!auth()->check()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'content' => 'required|array',
            'content.*' => 'nullable|string',
        ]);

        foreach ($validated['content'] as $key => $value) {
            AboutPageContent::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('about.edit')->with('success', __('About page content updated successfully.'));
    }
}
