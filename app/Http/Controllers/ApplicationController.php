<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function redirectToExternal(Job $job): RedirectResponse
    {
        $externalUrl = trim((string) $job->external_url);

        if ($externalUrl === '') {
            return redirect()->route('jobs.show', $job);
        }

        $externalUrl = $this->normalizeExternalUrl($externalUrl);

        $job->increment('apply_clicks');

        return redirect()->away($externalUrl);
    }

    private function normalizeExternalUrl(string $url): string
    {
        // Imported legacy data may come fully uppercased, so normalize for redirects.
        if (! preg_match('/[a-z]/', $url)) {
            return Str::lower($url);
        }

        $parts = parse_url($url);

        if ($parts === false || ! isset($parts['host'])) {
            return $url;
        }

        $scheme = isset($parts['scheme']) ? Str::lower($parts['scheme']).'://' : '';
        $host = Str::lower($parts['host']);
        $userInfo = isset($parts['user'])
            ? $parts['user'].(isset($parts['pass']) ? ':'.$parts['pass'] : '').'@'
            : '';
        $port = isset($parts['port']) ? ':'.$parts['port'] : '';
        $path = $parts['path'] ?? '';
        $query = isset($parts['query']) ? '?'.$parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#'.$parts['fragment'] : '';

        return $scheme.$userInfo.$host.$port.$path.$query.$fragment;
    }
}
