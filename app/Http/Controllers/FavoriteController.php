<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\FavoriteServiceInterface;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    private $favoriteService;

    public function __construct(FavoriteServiceInterface $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function addFavorite(Request $request): JsonResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'user_id' => 'required|integer|same:' . $user->id, // Vérifier que l'ID correspond à l'utilisateur connecté
            'phone' => 'required|string',
            'name' => 'required|string'
        ]);

        $favorite = $this->favoriteService->addFavorite($data);
        return response()->json($favorite);
    }

    public function removeFavorite(Request $request, int $favoriteId): JsonResponse
    {
        $user = Auth::user();
        $userId = $user->id;

        $success = $this->favoriteService->removeFavorite($userId, $favoriteId);

        return response()->json(['success' => $success]);
    }
}