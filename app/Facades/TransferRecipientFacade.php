<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TransferRecipientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'transfer.recipient.repository';
    }
}