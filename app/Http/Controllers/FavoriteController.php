<?php

namespace App\Http\Controllers;

use App\Interfaces\FavoriteServiceInterface;
use Illuminate\Http\Request;
use App\Services\FavoriteService;
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
        $data = $request->validate([
            'user_id' => 'required|integer',
            'phone' => 'required|string',
            'name' => 'required|string'
        ]);

        $favorite = $this->favoriteService->addFavorite($data);
        return response()->json($favorite);
    }

    public function removeFavorite(Request $request, int $favoriteId): JsonResponse
    {
        $userId = $request->input('user_id');
        $success = $this->favoriteService->removeFavorite($userId, $favoriteId);

        return response()->json(['success' => $success]);
    }
}
