<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }

}
