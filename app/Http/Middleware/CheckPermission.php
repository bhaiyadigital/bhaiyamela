<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{


    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

       
        if (str_contains($permission, '{module}')) {
            $module     = $request->route('module');
            $permission = str_replace('{module}', $module, $permission);
        }

        // Load roles and permissions once per request
        $user->loadMissing('roles.permissions');

        if (!$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}