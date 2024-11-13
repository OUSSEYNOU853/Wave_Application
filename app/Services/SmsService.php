<?php

namespace App\Services;

use Twilio\Rest\Client;

class SmsService
{
    protected $client;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $this->client = new Client($sid, $token);
    }

    public function sendSms($to, $message)
    {
        try {
            $this->client->messages->create(
                $to,
                [
                    'from' => env('TWILIO_PHONE_NUMBER'),
                    'body' => $message,
                ]
            );
        } catch (\Exception $e) {
            return null;
        }
    }
}
