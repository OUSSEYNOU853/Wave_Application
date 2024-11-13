<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AccountAction extends Model
{
    use Notifiable;
    protected $fillable = [
        'distributor_id',
        'client_id',
        'action',
        'amount'
    ];

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}