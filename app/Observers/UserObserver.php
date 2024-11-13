<?php

namespace App\Observers;

use App\Models\User;
use App\Events\UserCreated;
use App\Events\SendEmailEvent;

class UserObserver
{
    public function created(User $user)
    {
        $imageFile = request()->file('photo');
        event(new UserCreated($user, $imageFile));
        event(new SendEmailEvent($user));
    }
}
