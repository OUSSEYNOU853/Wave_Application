<?php

namespace App\Services;

use Exception;
use App\Enums\TransactionType;
use App\Services\AccountService;
use App\Facades\MerchantPaymentFacade;
use App\Interfaces\MerchantPaymentServiceInterface;

class MerchantPaymentService implements MerchantPaymentServiceInterface
{
    private AccountService $accountService;
    private TransactionHistoryService $transactionHistoryService;

    public function __construct(AccountService $accountService, TransactionHistoryService $transactionHistoryService)
    {
        $this->accountService = $accountService;
        $this->transactionHistoryService = $transactionHistoryService;
    }

    public function processPayment(array $paymentData)
    {
        $userId = $paymentData['user_id'];
        $merchantId = $paymentData['merchant_id'];
        $amount = $paymentData['amount'];

        // Vérifier le solde de l'utilisateur
        $this->accountService->checkSufficientBalance($userId, $amount);

        // Créer le paiement en attente
        $payment = MerchantPaymentFacade::create([
            'user_id' => $userId,
            'merchant_id' => $merchantId,
            'amount' => $amount,
            'status' => 'EN_ATTENTE'
        ]);

        // Débiter l'utilisateur
        $this->accountService->updateBalance($userId, $this->accountService->getBalance($userId) - $amount);

        // Créditer le marchand
        $this->accountService->updateBalance($merchantId, $this->accountService->getBalance($merchantId) + $amount);

        // Enregistrer la transaction dans l'historique
        $this->transactionHistoryService->logTransaction([
            'user_id' => $userId,
            'type' => TransactionType::PAIEMENT->value,
            'amount' => $amount,
            'details' => 'Paiement au marchand ' . $merchantId,
        ]);

        // Mettre à jour le statut du paiement
        MerchantPaymentFacade::update($payment->id, ['status' => 'TERMINER']);

        return $payment;
    }
}
