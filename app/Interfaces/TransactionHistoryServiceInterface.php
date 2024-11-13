<?php

namespace App\Interfaces;

interface TransactionHistoryServiceInterface
{
    public function logTransaction(array $transactionData);

    public function getUserHistory(int $userId);
}
