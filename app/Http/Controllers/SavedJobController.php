<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    public function store(Job $job)
    {
        SavedJob::firstOrCreate([
            'user_id' => Auth::id(),
            'job_listing_id' => $job->id,
        ]);
        return back()->with('status', 'Job saved');
    }

    public function destroy(Job $job)
    {
        SavedJob::where('user_id', Auth::id())
            ->where('job_listing_id', $job->id)
            ->delete();
        return back()->with('status', 'Job removed');
    }
}
