<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Transfer extends Model
{
    use Notifiable;
    protected $fillable = [
        'sender_id',
        'recipient_number',
        'amount',
        'status',
        'is_multiple',
        'scheduled_for'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->hasMany(TransferRecipient::class);
    }
}