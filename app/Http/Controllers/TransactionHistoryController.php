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

    public function getUserHistory(int $userId): JsonResponse
    {
        $history = $this->transactionHistoryService->getUserHistory($userId);

        return response()->json(['history' => $history]);
    }
}
