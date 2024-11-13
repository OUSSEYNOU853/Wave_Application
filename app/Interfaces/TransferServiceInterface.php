<?php

namespace App\Interfaces;

interface TransferServiceInterface
{
    public function makeTransfer(array $transferData);

    public function makeMultipleTransfer(int $senderId, array $recipients);
}
