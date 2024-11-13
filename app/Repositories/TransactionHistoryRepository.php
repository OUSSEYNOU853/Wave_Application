<?php

namespace App\Repositories;

use App\Models\TransactionHistory;
use App\Interfaces\TransactionHistoryRepositoryInterface;

class TransactionHistoryRepository implements TransactionHistoryRepositoryInterface
{
    public function all()
    {
        return TransactionHistory::all();
    }

    public function find($id)
    {
        return TransactionHistory::find($id);
    }

    public function create(array $data)
    {
        return TransactionHistory::create($data);
    }

    public function getUserTransactions($userId)
    {
        return TransactionHistory::where('user_id', $userId)->get();
    }

    public function getTransactionsByType($type)
    {
        return TransactionHistory::where('transaction_type', $type)->get();
    }
}