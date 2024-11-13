<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TransactionNotification;
use App\Interfaces\AccountActionServiceInterface;
use Illuminate\Support\Facades\Notification;

class AccountActionController extends Controller
{
    private $accountActionService;

    public function __construct(AccountActionServiceInterface $accountActionService)
    {
        $this->accountActionService = $accountActionService;
    }

    public function handleAction(Request $request)
    {
        // Récupérer le distributeur connecté
        $distributor = Auth::user();

        // Validation des données de la requête
        $data = $request->validate([
            'distributor_id' => 'required|integer',
            'client_id' => 'required|integer',
            'action' => 'required|string|in:depot,retrait',
            'amount' => 'required|numeric|min:0'
        ]);

        // Vérifier si le distributeur connecté correspond à celui de la requête
        if ($distributor->id !== (int) $data['distributor_id'] || $distributor->role !== 'distributeur') {
            return response()->json([
                'status' => 'error',
                'message' => 'Vous n\'êtes pas autorisé à effectuer cette opération.'
            ], 403);
        }

        try {
            $result = $this->accountActionService->processAction($data);

            if ($result['status'] === 'success') {
                // Récupérer le client
                $client = User::find($data['client_id']);
                
                if (!$client) {
                    throw new \Exception('Client introuvable');
                }

                $actionType = ucfirst($data['action']);
                $amount = number_format($data['amount'], 2, ',', ' ');
                
                $clientMessage = "{$actionType} de {$amount} FCFA effectué avec succès par le distributeur {$distributor->firstname} {$distributor->lastname}";
                $distributorMessage = "Vous avez effectué un {$data['action']} de {$amount} FCFA pour le client {$client->firstname} {$client->lastname}";

                Notification::send($client, new  TransactionNotification($clientMessage, ['sms', 'database']));
                Notification::send($distributor, new  TransactionNotification($clientMessage, ['sms', 'database']));
                $result['notifications'] = [
                    'client' => $clientMessage,
                    'distributor' => $distributorMessage
                ];
            }

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction non effectuer a cause d\'une erreur:' . $e->getMessage() . ', Veillez reéssayer'
            ], 500);
        }
    }
}