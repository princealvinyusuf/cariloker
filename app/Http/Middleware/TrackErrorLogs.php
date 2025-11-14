<?php

namespace App\Http\Middleware;

use App\Models\ErrorLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackErrorLogs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only track error responses (status code >= 400)
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 400) {
            $this->logError($request, $response, $statusCode);
        }
        
        return $response;
    }

    /**
     * Log error to database
     */
    protected function logError(Request $request, Response $response, int $statusCode): void
    {
        // Skip tracking for admin routes to avoid cluttering
        if ($request->is('admin/*')) {
            return;
        }

        $url = $request->fullUrl();
        $method = $request->method();
        $route = $request->route()?->getName();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();
        
        // Get error message from response if available
        $message = null;
        if ($response->getContent()) {
            $content = $response->getContent();
            // Try to extract meaningful error message
            if (strlen($content) < 500) {
                $message = strip_tags($content);
                $message = preg_replace('/\s+/', ' ', $message);
                $message = substr($message, 0, 500);
            }
        }

        // Find or create error log entry
        $errorLog = ErrorLog::where('status_code', $statusCode)
            ->where('method', $method)
            ->where('url', $url)
            ->first();

        if ($errorLog) {
            // Update existing error log
            $errorLog->increment('count');
            $errorLog->last_occurred_at = now();
            $errorLog->save();
        } else {
            // Create new error log
            ErrorLog::create([
                'status_code' => $statusCode,
                'method' => $method,
                'url' => $url,
                'route' => $route,
                'message' => $message,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'count' => 1,
                'first_occurred_at' => now(),
                'last_occurred_at' => now(),
            ]);
        }
    }
}
