<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\TransactionHistory;
use App\Interfaces\TransactionHistoryServiceInterface;

class TransactionHistoryService implements TransactionHistoryServiceInterface
{
    public function logTransaction(array $transactionData): TransactionHistory
    {
        $transactionType = $transactionData['type'] ?? TransactionType::DEPOT->value;

        return TransactionHistory::create([
            'user_id' => $transactionData['user_id'],
            'transaction_type' => $transactionType,
            'amount' => $transactionData['amount'],
            'status' => $transactionData['status'] ?? 'Terminé',
            'details' => $transactionData['details'] ?? null,
            'is_outgoing' => in_array($transactionType, [TransactionType::RETRAIT, TransactionType::TRANSFERT]),
        ]);
    }

    public function getUserHistory(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return TransactionHistory::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
    }
}