<?php

namespace App\Http\Middleware;

use App\Models\ErrorLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogHttpErrors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $status = $response->getStatusCode();
        } catch (\Throwable $e) {
            $status = null;
        }

        if ($status !== null && $status >= 400) {
            // Best-effort logging; ignore failures to avoid breaking the response
            try {
                ErrorLog::create([
                    'status_code' => $status,
                    'method' => $request->method(),
                    'path' => $request->path(),
                    'route_name' => optional($request->route())->getName(),
                    'ip_address' => $request->ip(),
                    'user_id' => Auth::id(),
                    'user_agent' => (string) $request->header('User-Agent'),
                    'message' => $this->buildMessage($status, $request),
                    'context' => [],
                ]);
            } catch (\Throwable $e) {
                // Swallow logging errors
            }
        }

        return $response;
    }

    private function buildMessage(int $status, Request $request): string
    {
        return sprintf(
            'HTTP %d on %s %s',
            $status,
            $request->method(),
            $request->path()
        );
    }
}


