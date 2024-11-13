<?php

namespace App\Interfaces;

interface AccountServiceInterface
{
    public function getBalance(int $userId): float;

    public function getLimit(int $userId): float;

    public function updateBalance(int $userId, float $amount): bool;

    public function updateLimit(int $userId, float $amount): bool;

    public function checkSufficientBalance(int $userId, float $amount): bool;
}
