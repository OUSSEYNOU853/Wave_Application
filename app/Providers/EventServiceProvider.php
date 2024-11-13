<?php

namespace App\Providers;

use App\Events\AuthEvent;
use App\Events\UserCreated;
use App\Events\SendEmailEvent;
use App\Listeners\SendSmsListener;
use App\Listeners\SendEmailListener;
use Illuminate\Auth\Events\Registered;
use App\Listeners\UploadUserProfileImage;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserCreated::class => [
            UploadUserProfileImage::class,
        ],
        AuthEvent::class => [
            SendSmsListener::class,
        ],
        SendEmailEvent::class => [
            SendEmailListener::class,
        ],
    ];

    public function boot()
    {
    }
}