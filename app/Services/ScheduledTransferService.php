<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Services\TransferService;
use App\Facades\ScheduledTransferFacade;
use App\Interfaces\ScheduledTransferServiceInterface;

class ScheduledTransferService implements ScheduledTransferServiceInterface
{
    private TransferService $transferService;
    private TransactionHistoryService $transactionHistoryService;

    public function __construct(TransferService $transferService, TransactionHistoryService $transactionHistoryService)
    {
        $this->transferService = $transferService;
        $this->transactionHistoryService = $transactionHistoryService;
    }

    public function scheduleTransfer(array $transferData)
    {
        $scheduledTransfer = ScheduledTransferFacade::create([
            'user_id' => $transferData['user_id'],
            'amount' => $transferData['amount'],
            'recipient_number' => $transferData['recipient_phone'],
            'frequency' => $transferData['frequency'],
            'next_transfer_date' => $transferData['next_date'],
            'is_active' => true
        ]);

        // Enregistrer la transaction planifiée dans l'historique
        $this->transactionHistoryService->logTransaction([
            'user_id' => $transferData['user_id'],
            'type' => TransactionType::TRANSFERT->value,
            'amount' => $transferData['amount'],
            'details' => 'Transfert planifié vers ' . $transferData['recipient_phone'],
            'status' => 'PLANIFIE',
        ]);

        return $scheduledTransfer;
    }
    
    public function processScheduledTransfers()
    {
        $transfers = ScheduledTransferFacade::where('is_active', true)
            ->where('next_transfer_date', '<=', now())
            ->get();

        foreach ($transfers as $transfer) {
            $this->transferService->makeTransfer([
                'sender_id' => $transfer->user_id,
                'recipient_phone' => $transfer->recipient_number,
                'amount' => $transfer->amount
            ]);

            $nextDate = $this->calculateNextDate($transfer->next_transfer_date, $transfer->frequency);

            $transfer->update([
                'next_transfer_date' => $nextDate
            ]);
        }
    }

    public function cancelScheduledTransfer($transferId)
    {
        ScheduledTransferFacade::update($transferId, [
            'is_active' => false
        ]);
    }

    public function getUserScheduledTransfers($userId)
    {
        return ScheduledTransferFacade::getUserScheduledTransfers($userId);
    }

    public function deleteInactiveTransfers()
    {
        //on doit d'abord verifie si is_active est a false
        $inactiveTransfers = ScheduledTransferFacade::where('is_active', false)->get();

        foreach ($inactiveTransfers as $transfer) {
            ScheduledTransferFacade::delete($transfer->id);
        }
    }

    public function getActiveTransfers()
    {
        return ScheduledTransferFacade::getActiveTransfers();
    }

    private function calculateNextDate($currentDate, $frequency)
    {
        return match ($frequency) {
            'JOUR' => $currentDate->addDay(),
            'SEMAINE' => $currentDate->addWeek(),
            'MOIS' => $currentDate->addMonth(),
            default => $currentDate
        };
    }
}
