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
        if ($user->role->value !== 'admin' && $user->role->value !== $role)
            return response()->json(['message' => 'you do not have access'], 403);

        return $next($request);
    }
}
