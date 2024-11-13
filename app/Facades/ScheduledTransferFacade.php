<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ScheduledTransferFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'scheduled.transfer.repository';
    }
}