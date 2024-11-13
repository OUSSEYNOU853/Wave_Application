<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ScheduledTransfer extends Model
{
    use Notifiable;
    protected $fillable = [
        'user_id',
        'amount',
        'recipient_number',
        'frequency',
        'next_transfer_date',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}