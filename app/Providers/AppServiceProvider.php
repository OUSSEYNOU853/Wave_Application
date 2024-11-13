<?php

namespace App\Providers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use App\Observers\UserObserver;
use App\Services\AccountService;
use App\Services\FavoriteService;
use App\Services\TransferService;
use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;
use App\Services\LocalStorageService;
use App\Services\AccountActionService;
use App\Repositories\AccountRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Repositories\FavoriteRepository;
use App\Repositories\TransferRepository;
use App\Services\MerchantPaymentService;
use App\Services\CloudinaryStorageService;
use App\Services\ScheduledTransferService;
use App\Interfaces\AccountServiceInterface;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Services\TransactionHistoryService;
use App\Interfaces\FavoriteServiceInterface;
use App\Interfaces\TransferServiceInterface;
use App\Repositories\AccountActionRepository;
use App\Interfaces\AccountRepositoryInterface;
use App\Interfaces\FavoriteRepositoryInterface;
use App\Interfaces\TransferRepositoryInterface;
use App\Repositories\MerchantPaymentRepository;
use App\Interfaces\AccountActionServiceInterface;
use App\Repositories\ScheduledTransferRepository;
use App\Repositories\TransferRecipientRepository;
use App\Repositories\TransactionHistoryRepository;
use App\Interfaces\MerchantPaymentServiceInterface;
use App\Interfaces\AccountActionRepositoryInterface;
use App\Interfaces\ScheduledTransferServiceInterface;
use App\Interfaces\MerchantPaymentRepositoryInterface;
use App\Interfaces\TransactionHistoryServiceInterface;
use App\Interfaces\ScheduledTransferRepositoryInterface;
use App\Interfaces\TransferRecipientRepositoryInterface;
use App\Interfaces\TransactionHistoryRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Lier chaque interface avec son implémentation
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind('user.repository', function ($app) {
            return $app->make(UserRepositoryInterface::class);
        }); 

        $this->app->bind(AccountServiceInterface::class, AccountService::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind('account.repository', function ($app) {
            return $app->make(AccountRepositoryInterface::class);
        });

        $this->app->bind(FavoriteServiceInterface::class, FavoriteService::class);
        $this->app->bind(FavoriteRepositoryInterface::class, FavoriteRepository::class);
        $this->app->bind('favorite.repository', function ($app) {
            return $app->make(FavoriteRepositoryInterface::class);
        });

        $this->app->bind(MerchantPaymentServiceInterface::class, MerchantPaymentService::class);
        $this->app->bind(MerchantPaymentRepositoryInterface::class, MerchantPaymentRepository::class);
        $this->app->bind('merchant.payment.repository', function ($app) {
            return $app->make(MerchantPaymentRepositoryInterface::class);
        });

        $this->app->bind(ScheduledTransferServiceInterface::class, ScheduledTransferService::class);
        $this->app->bind(ScheduledTransferRepositoryInterface::class, ScheduledTransferRepository::class);
        $this->app->bind('scheduled.transfer.repository', function ($app) {
            return $app->make(ScheduledTransferRepositoryInterface::class);
        });

        $this->app->bind(TransactionHistoryServiceInterface::class, TransactionHistoryService::class);
        $this->app->bind(TransactionHistoryRepositoryInterface::class, TransactionHistoryRepository::class);
        $this->app->bind('transaction.history.repository', function ($app) {
            return $app->make(TransactionHistoryRepositoryInterface::class);
        });

        $this->app->bind(TransferRecipientRepositoryInterface::class, TransferRecipientRepository::class);
        $this->app->bind('transfer.recipient.repository', function ($app) {
            return $app->make(TransferRecipientRepositoryInterface::class);
        });
        
        $this->app->bind(TransferServiceInterface::class, TransferService::class);
        $this->app->bind(TransferRepositoryInterface::class, TransferRepository::class);
        $this->app->bind('transfer.repository', function ($app) {
            return $app->make(TransferRepositoryInterface::class);
        });

        $this->app->bind(AccountActionServiceInterface::class, AccountActionService::class);
        $this->app->bind(AccountActionRepositoryInterface::class, AccountActionRepository::class);
        $this->app->bind('account.action.repository', function ($app) {
            return $app->make(AccountActionRepositoryInterface::class);
        });

        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->register(AuthCustomProvider::class);

        $this->app->singleton(CloudinaryStorageService::class, function ($app) {
            return new CloudinaryStorageService();
        });

        $this->app->singleton(LocalStorageService::class, function ($app) {
            return new LocalStorageService();
        });
    }

    public function boot()
    {
        User::observe(UserObserver::class);
    }
}
