<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $user = auth()->user();

        // Check if user has the required permission through their roles
        $hasPermission = $user->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission)
                  ->where('is_active', true);
        })->exists();

        if (!$hasPermission) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan tindakan ini.');
        }

        return $next($request);
    }
}
