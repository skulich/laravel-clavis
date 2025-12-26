<?php

declare(strict_types=1);

namespace SKulich\LaravelClavis\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

final class Clavis
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     *
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = config('clavis.key');
        $hash = is_string($key) ? base64_decode($key) : null;
        $token = $request->bearerToken();

        if (is_null($hash) || is_null($token) || ! Hash::check($token, $hash)) {
            throw new AuthenticationException(guards: ['clavis']);
        }

        return $next($request);
    }
}
