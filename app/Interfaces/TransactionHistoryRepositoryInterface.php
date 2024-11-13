<?php

namespace App\Interfaces;

interface TransactionHistoryRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function getUserTransactions($userId);
    public function getTransactionsByType($type);
}