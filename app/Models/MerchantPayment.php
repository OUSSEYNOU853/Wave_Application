<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MerchantPayment extends Model
{
    use Notifiable;
    protected $fillable = [
        'user_id',
        'merchant_id',
        'amount',
        'status'
    ];

    public function payer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }
}