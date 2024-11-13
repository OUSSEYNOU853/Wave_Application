<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Favorite extends Model
{
    use Notifiable;
    protected $fillable = [
        'user_id',
        'phone_number',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}