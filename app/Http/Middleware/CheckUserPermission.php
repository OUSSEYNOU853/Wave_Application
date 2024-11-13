<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Facades\UserFacade;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermission
{
    public function handle(Request $request, Closure $next)
    {
        // Récupérer l'utilisateur connecté via le token
        $connectedUser = Auth::user();

        if (!$connectedUser) {
            return response()->json([
                'status' => 'error',
                'message' => 'Utilisateur non authentifié',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $distributeurId = $request->route('distributor_id');  // Supposons que vous envoyez `distributor_id` dans l'URL
        if ($distributeurId !== $connectedUser->distributeur_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID de distributeur invalide pour l\'utilisateur connecté',
            ], Response::HTTP_FORBIDDEN);
        }

        // Si tout va bien, on continue l'exécution de la requête
        return $next($request);
    }
}
