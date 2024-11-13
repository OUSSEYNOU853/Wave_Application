<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Account extends Model
{
    use Notifiable;
    protected $fillable = [
        'user_id',
        'balance',
        'limit'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}