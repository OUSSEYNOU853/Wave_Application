<?php

namespace App\Services;

use App\Enums\ActionCompte;
use App\Enums\TransactionType;
use App\Services\AccountService;
use App\Facades\UserFacade;
use App\Facades\AccountActionFacade;
use App\Services\TransactionHistoryService;
use App\Interfaces\AccountActionServiceInterface;

class AccountActionService implements AccountActionServiceInterface
{
    private AccountService $accountService;
    private TransactionHistoryService $transactionHistoryService;

    public function __construct(AccountService $accountService, TransactionHistoryService $transactionHistoryService)
    {
        $this->accountService = $accountService;
        $this->transactionHistoryService = $transactionHistoryService;
    }

    public function processAction(array $data)
    {
        $distributorId = $data['distributor_id'] ?? null;
        $clientId = $data['client_id'] ?? null;
        $action = $data['action'] ?? null;
        $amount = $data['amount'] ?? null;

        if (!$distributorId || !$clientId || !$action) {
            throw new \InvalidArgumentException("Les champs 'distributor_id', 'client_id' et 'action' sont requis.");
        }
        if (!UserFacade::isDistributor($distributorId)) {
            throw new \RuntimeException("L'utilisateur $distributorId n'a pas de compte wave.");
        }
        if (!UserFacade::isClient($clientId)) {
            throw new \RuntimeException("L'utilisateur $clientId n'a pas de compte wave.");
        }

        $accountAction = AccountActionFacade::create([
            'distributor_id' => $distributorId,
            'client_id' => $clientId,
            'action' => $action,
            'amount' => $amount
        ]);

        switch ($action) {
            case ActionCompte::DEPOT->value:
                if ($amount && $amount > 0) {
                    $newBalance = $this->accountService->getBalance($clientId) + $amount;
                    $this->accountService->updateBalance($clientId, $newBalance);
                    $this->transactionHistoryService->logTransaction([
                        'user_id' => $clientId,
                        'transaction_type' => TransactionType::DEPOT->value,
                        'amount' => $amount,
                        'details' => 'Dépôt sur le compte',
                    ]);
                }
                break;

            case ActionCompte::RETRAIT->value:
                if ($amount && $amount > 0) {
                    $newBalance = $this->accountService->getBalance($clientId) - $amount;
                    if ($newBalance < 0) {
                        throw new \RuntimeException("Solde insuffisant pour le retrait.");
                    }
                    $this->accountService->updateBalance($clientId, $newBalance);

                    $this->transactionHistoryService->logTransaction([
                        'user_id' => $clientId,
                        'transaction_type' => TransactionType::RETRAIT->value,
                        'amount' => $amount,
                        'details' => 'Retrait du compte',
                    ]);
                }
                break;

            case 'Déplafonnement':
                if ($amount && $amount > 0) {
                    $this->accountService->updateLimit($clientId, $amount);
                }
                break;

            default:
                throw new \InvalidArgumentException("Action non reconnue: $action");
        }

        return $accountAction;
    }
}
