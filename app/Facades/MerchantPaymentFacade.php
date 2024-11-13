<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MerchantPaymentFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'merchant.payment.repository';
    }
}