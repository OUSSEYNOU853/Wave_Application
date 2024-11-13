<?php

namespace App\Http\Controllers;

use App\Interfaces\ScheduledTransferServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ScheduledTransferController extends Controller
{
    private $scheduledTransferService;

    public function __construct(ScheduledTransferServiceInterface $scheduledTransferService)
    {
        $this->scheduledTransferService = $scheduledTransferService;
    }

    public function scheduleTransfer(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'recipient_phone' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'frequency' => 'required|string|in:jour,semaine,mois',
            'next_date' => 'required|date'
        ]);
        $transfer = $this->scheduledTransferService->scheduleTransfer($data);
        return response()->json(['transfer' => $transfer, 'status' => 'scheduled']);
    }

    public function processTransfers(): JsonResponse
    {
        $this->scheduledTransferService->processScheduledTransfers();
        return response()->json(['status' => 'transfers processed']);
    }

    public function cancelTransfer($transferId): JsonResponse
    {
        $this->scheduledTransferService->cancelScheduledTransfer($transferId);
        return response()->json(['status' => 'transfer canceled']);
    }

    public function deleteInactiveTransfers(): JsonResponse
    {
        $this->scheduledTransferService->deleteInactiveTransfers();
        return response()->json(['status' => 'inactive transfers deleted']);
    }

    public function getActiveTransfers(): JsonResponse
    {
        $transfers = $this->scheduledTransferService->getActiveTransfers();
        return response()->json(['transfers' => $transfers]);
    }

    public function getUserScheduledTransfers($userId): JsonResponse
    {
        $transfers = $this->scheduledTransferService->getUserScheduledTransfers($userId);
        return response()->json(['transfers' => $transfers]);
    }
}
