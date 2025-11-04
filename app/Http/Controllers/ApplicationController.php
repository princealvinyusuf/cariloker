<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function store(Request $request, Job $job)
    {
        $data = $request->validate([
            'cover_letter' => ['nullable', 'string'],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }

        Application::create([
            'job_listing_id' => $job->id,
            'user_id' => Auth::id(),
            'cover_letter' => $data['cover_letter'] ?? null,
            'resume_path' => $resumePath,
            'status' => 'applied',
        ]);

        return back()->with('status', 'Application submitted successfully');
    }
}
