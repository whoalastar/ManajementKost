<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfTenant
{
    /**
     * Handle an incoming request.
     * Redirect ke dashboard jika sudah login sebagai tenant
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('tenant')->check()) {
            return redirect()->route('tenant.dashboard');
        }

        return $next($request);
    }
}
