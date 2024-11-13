<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuthEvent
{
    use Dispatchable, SerializesModels;

    public $phone;
    public $code;

    public function __construct($phone, $code)
    {
        $this->phone = $phone;
        $this->code = $code;
    }
}
