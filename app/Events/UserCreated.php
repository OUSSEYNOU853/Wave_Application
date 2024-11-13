<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class UserCreated
{
    use Dispatchable;

    public $user;
    public $profileImage;

    public function __construct(User $user, $profileImage = null)
    {
        $this->user = $user;
        $this->profileImage = $profileImage;
    }
    
}
