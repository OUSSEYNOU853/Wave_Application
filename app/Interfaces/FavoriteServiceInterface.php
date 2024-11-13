<?php

namespace App\Interfaces;

interface FavoriteServiceInterface
{
    public function addFavorite(array $data);

    public function removeFavorite(int $userId, int $favoriteId): bool;
}
