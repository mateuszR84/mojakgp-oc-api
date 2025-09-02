<?php

namespace StDevs\Kgp\Middleware;

use Closure;
use RainLab\User\Models\User;
use October\Rain\Auth\AuthException;

class JwtAuth
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token required'], 401);
        }

        try {
            $user = \Auth::getBearerUser($token);

            if (!$user) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            \Auth::setUser($user);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token verification failed'], 401);
        }
    }
}
