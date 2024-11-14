<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Notifications\PaymentNotification;
use App\Interfaces\MerchantPaymentServiceInterface;
use App\Notifications\TransactionNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class MerchantPaymentController extends Controller
{
    private $merchantPaymentService;
    private $smsService;

    public function __construct(MerchantPaymentServiceInterface $merchantPaymentService, SmsService $smsService)
    {
        $this->merchantPaymentService = $merchantPaymentService;
        $this->smsService = $smsService;
    }

    public function processPayment(Request $request): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        $data = $request->validate([
            'user_id' => "required|integer|same:{$user->id}",
            'merchant_id' => 'required|integer',
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            $payment = $this->merchantPaymentService->processPayment($data);
            $merchant = User::findOrFail($data['merchant_id']);
            $userMessage = "Vous avez effectué un paiement de " . number_format($data['amount'], 2, ',', ' ') . " FCFA chez le marchand.";
            $merchantMessage = "Vous avez reçu un paiement de " . number_format($data['amount'], 2, ',', ' ') . " FCFA de la part de " . $user->firstname . " " . $user->lastname . ".";

            $this->sendSmsNotification($user->phone, $userMessage);
            $this->sendSmsNotification($merchant->phone, $merchantMessage);

            Notification::send($user, new TransactionNotification($userMessage, ['sms', 'database']));
            Notification::send($merchant, new TransactionNotification($merchantMessage, ['sms', 'database']));

            return response()->json(['payment' => $payment, 'status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function sendSmsNotification(string $phoneNumber, string $message)
    {
        $this->smsService->sendSms($phoneNumber, $message);
    }
}