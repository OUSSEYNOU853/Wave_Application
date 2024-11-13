<?php

namespace App\Http\Controllers;

use App\Interfaces\MerchantPaymentServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\MerchantPaymentService;

class MerchantPaymentController extends Controller
{
    private $merchantPaymentService;

    public function __construct(MerchantPaymentServiceInterface $merchantPaymentService)
    {
        $this->merchantPaymentService = $merchantPaymentService;
    }

    public function processPayment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'merchant_id' => 'required|integer',
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            $payment = $this->merchantPaymentService->processPayment($data);
            return response()->json(['payment' => $payment, 'status' => 'success']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
