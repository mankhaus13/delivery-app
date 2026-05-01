<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     *
     */
    public static function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (in_array($request->user()->role, $roles, true)) {
            return $next($request);
        }
        return \response()->json('Resource not found', Response::HTTP_NOT_FOUND);
    }
}
