<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class FavoriteFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'favorite.repository';
    }
}