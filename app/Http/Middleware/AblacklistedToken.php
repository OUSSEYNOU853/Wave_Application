<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\BlacklistToken;
use Symfony\Component\HttpFoundation\Response;

class AblacklistedToken
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie que l'utilisateur est authentifié
        if ($request->user()) {
            // Vérifie si le token d'accès est dans la liste noire
            $blacklistedAccessToken = BlacklistToken::where('token', $request->bearerToken())
                ->where('type', 'access')
                ->first();

            if ($blacklistedAccessToken) {
                return response()->json([
                    'data' => null,
                    'status' => 'error',
                    'message' => 'Token révoqué.',
                ], Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }
}

