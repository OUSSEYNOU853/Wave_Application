<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'adresse',
        'photo',
        'cni',
        'secret_code',
        'role'
    ];

    protected $hidden = [
        'password',
        'secret_code',
    ];

    // Nouvelle relation avec Role
    // public function role()
    // {
    //     return $this->belongsTo(Role::class);
    // }

    // // Helper method pour vérifier le rôle
    // public function hasRole(string $roleName): bool
    // {
    //     return $this->role->name === $roleName;
    // }

    // Les autres relations restent inchangées
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function sentTransfers()
    {
        return $this->hasMany(Transfer::class, 'sender_id');
    }

    public function merchantPaymentsReceived()
    {
        return $this->hasMany(MerchantPayment::class, 'merchant_id');
    }

    public function merchantPaymentsMade()
    {
        return $this->hasMany(MerchantPayment::class, 'user_id');
    }

    public function transactionHistory()
    {
        return $this->hasMany(TransactionHistory::class);
    }

    public function accountActionsAsDistributor()
    {
        return $this->hasMany(AccountAction::class, 'distributor_id');
    }

    public function accountActionsAsClient()
    {
        return $this->hasMany(AccountAction::class, 'client_id');
    }

    public function scheduledTransfers()
    {
        return $this->hasMany(ScheduledTransfer::class);
    }
}