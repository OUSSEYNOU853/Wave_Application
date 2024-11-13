<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TransactionHistory extends Model
{
    use Notifiable;
    protected $fillable = [
        'user_id',
        'transaction_type',
        'amount',
        'status',
        'details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}