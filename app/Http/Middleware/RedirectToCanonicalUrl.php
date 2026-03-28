<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToCanonicalUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $appUrl = (string) config('app.url', '');
        $canonicalHost = (string) parse_url($appUrl, PHP_URL_HOST);
        $canonicalScheme = (string) parse_url($appUrl, PHP_URL_SCHEME);

        if ($canonicalHost === '' || $canonicalScheme === '') {
            return $next($request);
        }

        $requestHost = strtolower((string) $request->getHost());
        $requestScheme = strtolower((string) $request->getScheme());
        $normalizedCanonicalHost = strtolower($canonicalHost);
        $normalizedCanonicalScheme = strtolower($canonicalScheme);

        if (
            $requestHost !== $normalizedCanonicalHost
            || $requestScheme !== $normalizedCanonicalScheme
        ) {
            $targetUrl = $normalizedCanonicalScheme.'://'.$normalizedCanonicalHost.$request->getRequestUri();

            return redirect()->to($targetUrl, 301);
        }

        return $next($request);
    }
}
