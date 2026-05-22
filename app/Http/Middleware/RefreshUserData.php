<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshUserData
{
    /**
     * Handle an incoming request.
     * Refresh user data from database on every request to get latest points/status.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $request->setUserResolver(function () use ($request) {
                return $request->user()->fresh();
            });
        }

        return $next($request);
    }
}
