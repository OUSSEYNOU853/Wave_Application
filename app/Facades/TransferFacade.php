<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TransferFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'transfer.repository';
    }
}