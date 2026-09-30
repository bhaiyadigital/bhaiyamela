<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use App\Services\FacebookConversionApi;
use Illuminate\Support\Facades\View;

class TrackFacebookPageView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track GET requests that are not AJAX and are standard web pages
        if ($request->isMethod('GET') && !$request->ajax() && !$request->wantsJson()) {
            $eventId = 'event_' . time() . '_' . Str::random(10);
            
            // Share the eventId with views
            View::share('fbEventId', $eventId);
            
            // Send PageView event via CAPI
            FacebookConversionApi::sendEvent('PageView', $eventId);
        }

        return $next($request);
    }
}
