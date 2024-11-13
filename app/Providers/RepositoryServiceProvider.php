<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{
    UserRepositoryInterface,
    AccountRepositoryInterface,
    FavoriteRepositoryInterface,
    TransferRepositoryInterface,
    TransferRecipientRepositoryInterface,
    MerchantPaymentRepositoryInterface,
    TransactionHistoryRepositoryInterface,
    AccountActionRepositoryInterface,
    ScheduledTransferRepositoryInterface
};
use App\Repositories\{
    UserRepository,
    AccountRepository,
    FavoriteRepository,
    TransferRepository,
    TransferRecipientRepository,
    MerchantPaymentRepository,
    TransactionHistoryRepository,
    AccountActionRepository,
    ScheduledTransferRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind interfaces to implementations
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind('user.repository', function ($app) {
            return $app->make(UserRepositoryInterface::class);
        }); 

        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind('user.repository', function ($app) {
            return $app->make(AccountRepositoryInterface::class);
        }); 
        $this->app->bind(FavoriteRepositoryInterface::class, FavoriteRepository::class);
        $this->app->bind(TransferRepositoryInterface::class, TransferRepository::class);
        $this->app->bind(TransferRecipientRepositoryInterface::class, TransferRecipientRepository::class);
        $this->app->bind(MerchantPaymentRepositoryInterface::class, MerchantPaymentRepository::class);
        $this->app->bind(TransactionHistoryRepositoryInterface::class, TransactionHistoryRepository::class);
        $this->app->bind(AccountActionRepositoryInterface::class, AccountActionRepository::class);
        $this->app->bind(ScheduledTransferRepositoryInterface::class, ScheduledTransferRepository::class);

        // Facades bindings
        $this->app->bind('user.repository', fn($app) => $app->make(UserRepositoryInterface::class));
        $this->app->bind('account.repository', fn($app) => $app->make(AccountRepositoryInterface::class));
        $this->app->bind('favorite.repository', fn($app) => $app->make(FavoriteRepositoryInterface::class));
        $this->app->bind('transfer.repository', fn($app) => $app->make(TransferRepositoryInterface::class));
        $this->app->bind('transfer.recipient.repository', fn($app) => $app->make(TransferRecipientRepositoryInterface::class));
        $this->app->bind('merchant.payment.repository', fn($app) => $app->make(MerchantPaymentRepositoryInterface::class));
        $this->app->bind('transaction.history.repository', fn($app) => $app->make(TransactionHistoryRepositoryInterface::class));
        $this->app->bind('account.action.repository', fn($app) => $app->make(AccountActionRepositoryInterface::class));
        $this->app->bind('scheduled.transfer.repository', fn($app) => $app->make(ScheduledTransferRepositoryInterface::class));
    }
}