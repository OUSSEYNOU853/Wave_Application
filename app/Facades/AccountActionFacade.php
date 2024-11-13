<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AccountActionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'account.action.repository';
    }
}