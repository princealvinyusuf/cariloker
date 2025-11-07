<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProtectAgainstScraping
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $blockedAgents = config('scraper.blocked_user_agents', []);
        $userAgent = (string) $request->userAgent();
        $normalizedAgent = Str::lower($userAgent);

        foreach ($blockedAgents as $pattern) {
            if ($pattern !== '' && Str::contains($normalizedAgent, Str::lower($pattern))) {
                abort(403, __('Access denied'));
            }
        }

        $suspicious = false;

        if ($userAgent === '') {
            $suspicious = true;
        }

        $accept = Str::lower($request->header('accept', ''));
        $allowedContentTypes = config('scraper.allowed_content_types', ['text/html', 'application/xhtml+xml', 'application/json']);
        if ($accept !== '') {
            $hasAllowed = false;
            foreach ($allowedContentTypes as $contentType) {
                if (Str::contains($accept, Str::lower($contentType))) {
                    $hasAllowed = true;
                    break;
                }
            }
            if (!$hasAllowed) {
                $suspicious = true;
            }
        }

        if (config('scraper.require_accept_language', true) && !$request->hasHeader('Accept-Language')) {
            $suspicious = true;
        }

        if ($suspicious) {
            $key = 'scraper:suspicious:' . sha1($request->ip() . '|' . $normalizedAgent);
            $maxAttempts = (int) config('scraper.allowed_suspicious_attempts', 3);
            $decaySeconds = (int) config('scraper.decay_seconds', 600);

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                abort(403, __('Access denied'));
            }

            RateLimiter::hit($key, $decaySeconds);
        }

        return $next($request);
    }
}


