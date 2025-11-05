<?php

namespace App\Http\Controllers;

use App\Models\TermsOfServiceContent;
use Illuminate\Http\Request;

class TermsOfServiceController extends Controller
{
    /**
     * Display the Terms of Service page
     */
    public function index()
    {
        $contents = TermsOfServiceContent::orderBy('section')->orderBy('order')->get()->keyBy('key');
        
        // If no content exists, seed default content
        if ($contents->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'TermsOfServiceContentSeeder']);
            $contents = TermsOfServiceContent::orderBy('section')->orderBy('order')->get()->keyBy('key');
        }
        
        return view('terms-of-service.index', compact('contents'));
    }

    /**
     * Show the form for editing Terms of Service content
     */
    public function edit()
    {
        if (!auth()->check()) {
            abort(403);
        }
        
        $contents = TermsOfServiceContent::orderBy('section')->orderBy('order')->get();
        
        // If no content exists, seed default content
        if ($contents->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'TermsOfServiceContentSeeder']);
            $contents = TermsOfServiceContent::orderBy('section')->orderBy('order')->get();
        }
        
        $sections = $contents->groupBy('section');
        
        return view('terms-of-service.edit', compact('sections', 'contents'));
    }

    /**
     * Update the Terms of Service content
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
            TermsOfServiceContent::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('terms-of-service.edit')->with('success', __('Terms of Service content updated successfully.'));
    }
}
