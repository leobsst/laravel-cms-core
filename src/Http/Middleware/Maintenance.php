<?php

namespace Leobsst\LaravelCmsCore\Http\Middleware;

use Leobsst\LaravelCmsCore\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->hasRole('manager')) {
            return $next($request);
        } elseif (Setting::get('under_maintenance') == '0') {
            return $next($request);
        } else {
            return response()
                ->view('errors.503', [
                    'websiteName' => Setting::get('website_name'),
                ], 503);
        }
    }
}
