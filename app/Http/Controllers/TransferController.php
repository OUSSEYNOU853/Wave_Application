<?php

namespace App\Http\Controllers;

use App\Interfaces\TransferServiceInterface;
use Illuminate\Http\Request;
use App\Services\TransferService;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\JsonResponse;

class TransferController extends Controller
{
    private $transferService;

    public function __construct(TransferServiceInterface $transferService)
    {
        $this->transferService = $transferService;
    }

    public function makeTransfer(Request $request): JsonResponse
    {
        $data = $request->validate([
            'sender_id' => 'required|integer',
            'recipient_phone' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $transfer = $this->transferService->makeTransfer($data);

        return response()->json(['transfer' => $transfer, 'status' => 'initiated', 'message' => 'Transfer initiated successfully'  ]);
    }

    public function makeMultipleTransfer(Request $request): JsonResponse
    {
        $data = $request->validate([
            'sender_id' => 'required|integer',
            'recipients' => 'required|array',
            'recipients.*.phone' => 'required|string',
            'recipients.*.amount' => 'required|numeric|min:0.01',
        ]);

        $transfer = $this->transferService->makeMultipleTransfer($data['sender_id'], $data['recipients']);

        return response()->json(['transfer' => $transfer, 'status' => 'initiated', 'message' => 'Multiple transfer initiated successfully']);
    }
}
