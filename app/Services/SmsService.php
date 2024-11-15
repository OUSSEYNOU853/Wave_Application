<?php

namespace App\Services;

use Twilio\Rest\Client;

class SmsService
{
    protected $client;
    protected $message;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $this->client = new Client($sid, $token);
    }

    public function content($message)
    {
        $this->message = $message;
        return $this;
    }

    public function sendSms($to)
    {
        try {
            $this->client->messages->create(
                $to,
                [
                    'from' => env('TWILIO_PHONE_NUMBER'),
                    'body' => $this->message,
                ]
            );
        } catch (\Exception $e) {
            return null;
        }
    }
}

