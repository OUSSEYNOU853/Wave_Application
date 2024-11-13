<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class TokenUserMiddleware
{
    public function handle($request, Closure $next)
    {
        // Récupérer le token depuis l'en-tête Authorization
        $token = $request->bearerToken();

        if ($token) {
            try {
                $decoded = JWT::decode($token, new Key(config('app.jwt_secret'), 'HS256'));

                $request->attributes->set('userId', $decoded->sub);
                Auth::loginUsingId($decoded->sub);
            } catch (\Exception $e) {
                Log::error('Token decoding error: ' . $e->getMessage());
                return response()->json(['error' => 'Token invalide'], 401);
            }
        } else {
            return response()->json(['error' => 'Token manquant'], 401);
        }

        return $next($request);
    }
}
