<?php

declare(strict_types=1);

namespace SKulich\LaravelClavis\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        $key = config('clavis.hash');
        $hash = is_string($key) ? (base64_decode($key, true) ?: null) : null;
        $token = $request->bearerToken();

        if (is_null($hash) || is_null($token) || ! Hash::check($token, $hash)) {
            event(new Failed('clavis', null, [
                'token' => $token ? Str::mask($token, '*', 0, -4) : null,
            ]));

            throw new AuthenticationException(guards: ['clavis']);
        }

        return $next($request);
    }
}
