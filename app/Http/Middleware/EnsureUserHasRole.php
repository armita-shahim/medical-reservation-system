<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        $roles = explode('/', $role);
        if (!in_array($user->role->value, $roles, true))
            return response()->json(['message' => 'you do not have access'], 403);

        return $next($request);
    }
}
