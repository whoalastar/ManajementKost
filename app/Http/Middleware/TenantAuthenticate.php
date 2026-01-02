<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TenantAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('tenant')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('tenant.login');
        }

        $tenant = Auth::guard('tenant')->user();

        // Cek apakah tenant masih aktif
        if ($tenant->status !== 'active') {
            Auth::guard('tenant')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('tenant.login')
                ->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi admin.']);
        }

        return $next($request);
    }
}
