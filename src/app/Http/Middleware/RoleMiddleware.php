<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user() ?? Auth::guard('admin')->user() ?? Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route(
                $request->is('admin*') || $request->is('admin-panel*')
                    ? 'filament.admin.auth.login'
                    : 'login'
            );
        }

        // super_admin (Spatie role) atau kolom role ada di list yang diizinkan
        if ($user->hasRole('super_admin') || in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
