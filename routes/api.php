<?php

use App\Http\Controllers\{
    UserController,
    TransferController,
    MerchantPaymentController,
    FavoriteController,
    TransactionHistoryController,
    AccountActionController,
    AuthController,
    ScheduledTransferController
};
use App\Http\Middleware\AblacklistedToken;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    
    Route::post('/loginClient', [AuthController::class, 'loginClient']);
    Route::post('/verify-sms-code', [AuthController::class, 'verifySmsCode']);
    Route::post('/loginDistributeur', [AuthController::class, 'loginDistributeur']);
    Route::post('/updateDistributeur', [AuthController::class, 'updateFirstLoginCredentials']);
    Route::post('/register', [UserController::class, 'register']);
    
    Route::middleware([Authenticate::class, AblacklistedToken::class])->group(function () {
        Route::prefix('users')->group(function () {
            Route::post('/refresh', [AuthController::class, 'refresh']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/{phone}', [UserController::class, 'findByPhone']);
            Route::put('/{id}', [UserController::class, 'updateProfile']);
        });

        // Routes pour les favoris
        Route::prefix('favorites')->group(function () {
            Route::post('/', [FavoriteController::class, 'addFavorite']);
            Route::delete('/{id}', [FavoriteController::class, 'removeFavorite']);
        });

        // Routes pour les paiements marchand
        Route::post('/merchant-payments', [MerchantPaymentController::class, 'processPayment']);

        // Routes pour les transferts programmés
        Route::prefix('scheduled')->group(function () {
            Route::post('/', [ScheduledTransferController::class, 'scheduleTransfer']);
            Route::get('/process', [ScheduledTransferController::class, 'processTransfers']);
            Route::delete('/{transferId}', [ScheduledTransferController::class, 'cancelTransfer']);
            Route::get('/active', [ScheduledTransferController::class, 'getActiveTransfers']);
            Route::get('/{userId}', [ScheduledTransferController::class, 'getUserScheduledTransfers']);
            Route::delete('/delete', [ScheduledTransferController::class, 'deleteInactiveTransfers']);
        });

        // Routes pour l'historique des transactions
        Route::prefix('transactions')->group(function () {
            Route::post('/', [TransactionHistoryController::class, 'logTransaction']);
            Route::get('/{userId}', [TransactionHistoryController::class, 'getUserHistory']);
        });

        // Routes pour les transferts
        Route::prefix('transfers')->group(function () {
            Route::post('/', [TransferController::class, 'makeTransfer']);
            Route::post('/multiple', [TransferController::class, 'makeMultipleTransfer']);
        });
        
        Route::prefix('account')->group(function () {
            Route::post('/action', [AccountActionController::class, 'handleAction']);
        });
    });
});
