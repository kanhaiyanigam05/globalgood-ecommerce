<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedGuard
{
    public function handle(Request $request, Closure $next, string $guard)
    {
        if (Auth::guard($guard)->check()) {

            return match ($guard) {
                'admin' => redirect()->route('admin.dashboard'),
                'vendor' => redirect()->route('vendor.dashboard'),
                default => redirect('/')
            };
        }

        return $next($request);
    }
}
