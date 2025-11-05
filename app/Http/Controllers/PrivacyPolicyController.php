<?php

namespace App\Http\Controllers;

use App\Models\PrivacyPolicyContent;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    /**
     * Display the Privacy Policy page
     */
    public function index()
    {
        $contents = PrivacyPolicyContent::orderBy('section')->orderBy('order')->get()->keyBy('key');
        
        // If no content exists, seed default content
        if ($contents->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'PrivacyPolicyContentSeeder']);
            $contents = PrivacyPolicyContent::orderBy('section')->orderBy('order')->get()->keyBy('key');
        }
        
        return view('privacy-policy.index', compact('contents'));
    }

    /**
     * Show the form for editing Privacy Policy content
     */
    public function edit()
    {
        if (!auth()->check()) {
            abort(403);
        }
        
        $contents = PrivacyPolicyContent::orderBy('section')->orderBy('order')->get();
        
        // If no content exists, seed default content
        if ($contents->isEmpty()) {
            \Artisan::call('db:seed', ['--class' => 'PrivacyPolicyContentSeeder']);
            $contents = PrivacyPolicyContent::orderBy('section')->orderBy('order')->get();
        }
        
        $sections = $contents->groupBy('section');
        
        return view('privacy-policy.edit', compact('sections', 'contents'));
    }

    /**
     * Update the Privacy Policy content
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
            PrivacyPolicyContent::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('privacy-policy.edit')->with('success', __('Privacy Policy content updated successfully.'));
    }
}
