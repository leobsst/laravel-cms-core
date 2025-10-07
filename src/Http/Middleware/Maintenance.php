<?php

namespace Leobsst\LaravelCmsCore\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Leobsst\LaravelCmsCore\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request):Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return match (true) {
            Auth::check() && Auth::user()->hasRole('manager'),
            Setting::get('under_maintenance') == '0' => $next($request),
            default => abort(503, 'Site is under maintenance. Please try again later.'),
        };
    }
}
