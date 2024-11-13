<?php

namespace App\Http\Controllers;

use App\Interfaces\TransactionHistoryServiceInterface;
use Illuminate\Http\Request;
use App\Services\TransactionHistoryService;
use Illuminate\Http\JsonResponse;

class TransactionHistoryController extends Controller
{
    private $transactionHistoryService;

    public function __construct(TransactionHistoryServiceInterface $transactionHistoryService)
    {
        $this->transactionHistoryService = $transactionHistoryService;
    }

    public function logTransaction(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'details' => 'nullable|string'
        ]);

        $transaction = $this->transactionHistoryService->logTransaction($data);

        return response()->json(['transaction' => $transaction, 'status' => 'logged']);
    }

    public function getUserHistory(int $userId): JsonResponse
    {
        $history = $this->transactionHistoryService->getUserHistory($userId);

        return response()->json(['history' => $history]);
    }
}
