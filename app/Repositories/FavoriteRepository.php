<?php

namespace App\Repositories;

use App\Models\Favorite;
use App\Interfaces\FavoriteRepositoryInterface;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    public function all()
    {
        return Favorite::all();
    }

    public function find($id)
    {
        return Favorite::find($id);
    }

    public function create(array $data)
    {
        return Favorite::create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    public function delete($id)
    {
        return Favorite::destroy($id);
    }

    public function getUserFavorites($userId)
    {
        return Favorite::where('user_id', $userId)->get();
    }
}
