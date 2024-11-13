<?php

namespace App\Services;

use Exception;
use App\Facades\AccountFacade;
use App\Interfaces\AccountServiceInterface;

class AccountService implements AccountServiceInterface
{
    public function getBalance(int $userId): float
    {
        $account = AccountFacade::findByUserId($userId);
        return $account ? $account->balance : 0.0;
    }

    public function getLimit(int $userId): float
    {
        $account = AccountFacade::findByUserId($userId);
        return $account ? $account->limit : 200000.00;
    }

    public function updateBalance(int $userId, float $amount): bool
    {
        $account = AccountFacade::findByUserId($userId);
        if ($account) {
            return AccountFacade::updateBalance($account->id, $amount);
        }
        return false;
    }

    public function updateLimit(int $userId, float $amount): bool
    {
        $account = AccountFacade::findByUserId($userId);
        if ($account) {
            return AccountFacade::updateLimit($account->id, $amount);
        }
        return false;
    }

    public function checkSufficientBalance(int $userId, float $amount): bool
    {
        $balance = $this->getBalance($userId);
        if ($balance < $amount) {
            throw new Exception("Insufficient balance for user ID: $userId.");
        }
        return true;
    }
}
