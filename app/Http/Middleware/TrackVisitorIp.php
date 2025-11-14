<?php

namespace App\Http\Middleware;

use App\Models\VisitorIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking for admin routes and API routes
        if ($request->is('admin/*') || $request->is('api/*')) {
            return $next($request);
        }

        $ipAddress = $request->ip();
        
        // Skip if IP is localhost or invalid
        if (empty($ipAddress) || $ipAddress === '127.0.0.1' || $ipAddress === '::1') {
            return $next($request);
        }

        // Track visitor IP (update or create)
        $visitor = VisitorIp::firstOrNew(['ip_address' => $ipAddress]);
        
        if ($visitor->exists) {
            // Update existing visitor
            $visitor->increment('visit_count');
            $visitor->last_visited_at = now();
            $visitor->save();
        } else {
            // Create new visitor
            $visitor->first_visited_at = now();
            $visitor->last_visited_at = now();
            $visitor->visit_count = 1;
            $visitor->save();
        }

        return $next($request);
    }
}
