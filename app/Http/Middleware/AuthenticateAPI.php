<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

use function in_array;

class AuthenticateAPI
{
    public function __construct(private readonly ConfigRepository $config)
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     *
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestToken = $request->bearerToken();
        if ($requestToken === null) {
            return \response()->json('Invalid token', Response::HTTP_FORBIDDEN);
        }

        $validTokens = $this->config->get('api.tokens');

        if (!in_array($requestToken, $validTokens, true)) {
            return \response()->json('Invalid token', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
