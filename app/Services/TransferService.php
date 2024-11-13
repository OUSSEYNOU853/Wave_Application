<?php

namespace App\Services;

use App\Facades\UserFacade;
use App\Enums\TransactionType;
use App\Facades\TransferFacade;
use App\Facades\TransferRecipientFacade;
use App\Interfaces\TransferServiceInterface;

class TransferService implements TransferServiceInterface
{
    public function __construct(
        private AccountService $accountService,
        private TransactionHistoryService $transactionHistoryService
    ) {}

    public function makeTransfer(array $transferData)
    {
        $senderId = $transferData['sender_id'];
        $recipientPhone = $transferData['recipient_phone'];
        $amount = $transferData['amount'];

        // Vérifier le solde
        $this->accountService->checkSufficientBalance($senderId, $amount);

        // Créer le transfert
        $transfer = TransferFacade::create([
            'sender_id' => $senderId,
            'recipient_number' => $recipientPhone,
            'amount' => $amount,
            'status' => 'En attente'
        ]);

        // Débiter le compte de l'expéditeur
        $this->accountService->updateBalance(
            $senderId,
            $this->accountService->getBalance($senderId) - $amount
        );

        // Si le destinataire existe, créditer son compte
        $recipient = UserFacade::findByPhone($recipientPhone);
        if ($recipient) {
            $this->accountService->updateBalance(
                $recipient->id,
                $this->accountService->getBalance($recipient->id) + $amount
            );
            
            // Mettre à jour le statut du transfert
            TransferFacade::update($transfer->id, ['status' => 'Terminé']);

            $this->transactionHistoryService->logTransaction([
                'user_id' => $transferData['sender_id'],
                'type' => TransactionType::TRANSFERT->value,
                'amount' => $transferData['amount'],
                'details' => 'Transfert vers ' . $transferData['recipient_phone'],
            ]);
    
            // Si le transfert est vers un autre utilisateur, enregistrer également le transfert côté destinataire
            if (isset($transferData['recipient_id'])) {
                $this->transactionHistoryService->logTransaction([
                    'user_id' => $transferData['recipient_id'],
                    'type' => TransactionType::TRANSFERT->value,
                    'amount' => $transferData['amount'],
                    'details' => 'Transfert reçu de ' . $transferData['sender_phone'],
                ]);
            }
        }

        return $transfer;
    }

    public function makeMultipleTransfer(int $senderId, array $recipients)
    {
        $totalAmount = collect($recipients)->sum('amount');
        
        // Vérifier le solde total
        $this->accountService->checkSufficientBalance($senderId, $totalAmount);

        // Créer le transfert principal
        $transfer = TransferFacade::create([
            'sender_id' => $senderId,
            'amount' => $totalAmount,
            'recipient_number' => null,
            'is_multiple' => true,
            'status' => 'En attente'
        ]);

        // Créer les destinataires
        foreach ($recipients as $recipient) {
            TransferRecipientFacade::create([
                'transfer_id' => $transfer->id,
                'recipient_number' => $recipient['phone'],
                'amount' => $recipient['amount']
            ]);

            // Enregistrer chaque transfert dans l'historique
            $this->transactionHistoryService->logTransaction([
                'user_id' => $senderId,
                'type' => TransactionType::TRANSFERT->value,
                'amount' => $recipient['amount'],
                'details' => 'Transfert vers ' . $recipient['phone'],
            ]);

            if (isset($transferData['recipient_id'])) {
                $this->transactionHistoryService->logTransaction([
                    'user_id' => $recipient['recipient_id'],
                    'type' => TransactionType::TRANSFERT->value,
                    'amount' => $recipient['amount'],
                    'details' => 'Transfert reçu de ' . $recipient['sender_phone'],
                ]);
            }
        }

        // Traiter chaque transfert
        foreach ($recipients as $recipient) {
            $this->makeTransfer([
                'sender_id' => $senderId,
                'recipient_phone' => $recipient['phone'],
                'amount' => $recipient['amount']
            ]);
        }

        return $transfer;
    }
}
