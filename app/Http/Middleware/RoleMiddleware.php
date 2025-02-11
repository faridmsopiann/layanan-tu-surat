<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            \Log::info('User not authenticated.');
            abort(403, 'Unauthorized action.');
        }

        $user = Auth::user();
        \Log::info('Checking role for user: ' . $user->id);

        if (!$user->hasRole($role)) {
            \Log::info('User does not have role: ' . $role);
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
