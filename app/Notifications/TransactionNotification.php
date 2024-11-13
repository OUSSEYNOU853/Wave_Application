<?php

namespace App\Notifications;

use App\Services\SmsService;
use App\Mail\TransactionMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransactionNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $message;
    protected $channels;

    public function __construct($message, array $channels = ['database', 'sms', 'mail'])
    {
        $this->message = $message;
        $this->channels = $channels;
    }

    public function via($notifiable)
    {
        return $this->channels;
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
        ];
    }

    public function toSms($notifiable)
    {
        $smsService = new SmsService();
        $smsService->sendSms($notifiable->phone, $this->message);
    }

    public function toMail($notifiable)
    {
        return (new TransactionMail($this->message))
            ->to($notifiable->email);
    }
}