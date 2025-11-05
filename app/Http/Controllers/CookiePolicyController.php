<?php

namespace App\Http\Controllers;

use App\Models\CookiePolicyContent;
use Illuminate\Http\Request;

class CookiePolicyController extends Controller
{
    /**
     * Display the Cookie Policy page
     */
    public function index()
    {
        $contents = CookiePolicyContent::orderBy('section')->orderBy('order')->get()->keyBy('key');
        
        // If no content exists, seed default content
        if ($contents->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'CookiePolicyContentSeeder']);
            $contents = CookiePolicyContent::orderBy('section')->orderBy('order')->get()->keyBy('key');
        }
        
        return view('cookie-policy.index', compact('contents'));
    }

    /**
     * Show the form for editing Cookie Policy content
     */
    public function edit()
    {
        if (!auth()->check()) {
            abort(403);
        }
        
        $contents = CookiePolicyContent::orderBy('section')->orderBy('order')->get();
        
        // If no content exists, seed default content
        if ($contents->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'CookiePolicyContentSeeder']);
            $contents = CookiePolicyContent::orderBy('section')->orderBy('order')->get();
        }
        
        $sections = $contents->groupBy('section');
        
        return view('cookie-policy.edit', compact('sections', 'contents'));
    }

    /**
     * Update the Cookie Policy content
     */
    public function update(Request $request)
    {
        if (!auth()->check()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'content' => 'required|array',
            'content.*' => 'nullable|string',
        ]);

        foreach ($validated['content'] as $key => $value) {
            CookiePolicyContent::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('cookie-policy.edit')->with('success', __('Cookie Policy content updated successfully.'));
    }
}
