<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
	/**
	 * Handle an incoming request.
	 */
    public function handle(Request $request, Closure $next)
    {
        $allowedLocales = ['id', 'en'];

        // Session might not be started in some contexts; guard it
        $sessionLocale = $request->hasSession() ? $request->session()->get('locale_v2') : null;

        $locale = in_array($sessionLocale, $allowedLocales, true)
            ? $sessionLocale
            : config('app.locale', 'id');

        app()->setLocale($locale);

        return $next($request);
    }
}


