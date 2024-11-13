<?php

namespace App\Interfaces;

interface MerchantPaymentServiceInterface
{
    public function processPayment(array $paymentData);
}
