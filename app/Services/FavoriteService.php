<?php

namespace App\Services;

use App\Facades\FavoriteFacade;
use App\Interfaces\FavoriteServiceInterface;

class FavoriteService implements FavoriteServiceInterface
{
    public function addFavorite(array $data)
    {
        return FavoriteFacade::create([
            'user_id' => $data['user_id'],
            'phone_number' => $data['phone'],
            'name' => $data['name']
        ]);
    }

    public function removeFavorite(int $userId, int $favoriteId): bool
    {
        $favorite = FavoriteFacade::find($favoriteId);
        if ($favorite && $favorite->user_id === $userId) {
            return FavoriteFacade::delete($favoriteId);
        }
        return false;
    }
}
