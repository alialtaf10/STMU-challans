<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        \Log::info('Middleware Role Check:', [
            'user_id' => optional($request->user())->id,
            'user_role' => optional($request->user())->role,
            'expected_roles' => $roles,
        ]);

        if (!$user || !in_array($user->role, $roles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}