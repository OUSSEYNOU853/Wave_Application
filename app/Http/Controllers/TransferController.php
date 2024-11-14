<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transfer;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TransferNotification;
use App\Interfaces\TransferServiceInterface;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TransactionNotification;

class TransferController extends Controller
{
    private $transferService;
    private $smsService;

    public function __construct(TransferServiceInterface $transferService, SmsService $smsService)
    {
        $this->transferService = $transferService;
        $this->smsService = $smsService;
    }

    public function makeTransfer(Request $request): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        $data = $request->validate([
            'sender_id' => 'required|integer|same:' . $user->id,
            'recipient_phone' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $transfer = $this->transferService->makeTransfer($data);

        // Envoyer les notifications par SMS
        $this->sendSenderNotification($data['sender_id'], $data['recipient_phone'], $data['amount']);
        $this->sendRecipientNotification($data['recipient_phone'], $data['amount'], $transfer->id);

        return response()->json(['transfer' => $transfer, 'status' => 'initiated', 'message' => 'Transfer initiated successfully']);
    }

    public function makeMultipleTransfer(Request $request): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        $data = $request->validate([
            'sender_id' => 'required|integer|same:' . $user->id,
            'recipients' => 'required|array',
            'recipients.*.phone' => 'required|string',
            'recipients.*.amount' => 'required|numeric|min:0.01',
        ]);

        $transfer = $this->transferService->makeMultipleTransfer($data['sender_id'], $data['recipients']);

        // Envoyer les notifications par SMS
        foreach ($data['recipients'] as $recipient) {
            $this->sendSenderNotification($data['sender_id'], $recipient['phone'], $recipient['amount']);
            $this->sendRecipientNotification($recipient['phone'], $recipient['amount'], $transfer->id);
        }

        return response()->json(['transfer' => $transfer, 'status' => 'initiated', 'message' => 'Multiple transfer initiated successfully']);
    }

    private function sendSenderNotification(int $senderId, string $recipientPhone, float $amount)
    {
        $sender = User::findOrFail($senderId);
        $message = "Vous avez effectué un transfert de " . number_format($amount, 2, ',', ' ') . " FCFA pour le numéro " . $recipientPhone;
        $this->smsService->sendSms($sender->phone, $message);
        $sender->notify(new TransactionNotification($message, ['sms', 'database']));
    }

    private function sendRecipientNotification(string $recipientPhone, float $amount, int $transferId)
    {
        $recipient = User::wherePhone($recipientPhone)->first();
        if ($recipient) {
            $message = "Vous avez reçu un transfert de " . number_format($amount, 2, ',', ' ') . " FCFA. Référence de la transaction : #" . $transferId;
            $this->smsService->sendSms($recipient->phone, $message);
            $recipient->notify(new TransactionNotification($message, ['sms', 'database']));
        }
    }
}