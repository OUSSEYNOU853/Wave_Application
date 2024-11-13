<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TransferRecipient extends Model
{
    use Notifiable;
    protected $fillable = [
        'transfer_id',
        'recipient_number',
        'amount'
    ];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}