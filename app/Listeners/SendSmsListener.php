<?php

namespace App\Listeners;

use App\Events\AuthEvent;
use App\Jobs\SendSmsJob;

class SendSmsListener
{
    public function handle(AuthEvent $event)
    {
        // Exécute le job pour envoyer le SMS
        SendSmsJob::dispatch($event->phone, "Votre code de connexion est : {$event->code}");
    }
}
