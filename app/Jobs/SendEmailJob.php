<?php

namespace App\Jobs;

use App\Mail\TransactionMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $message;
    protected $pdfPath;

    public function __construct($email, $message, $pdfPath)
    {
        $this->email = $email;
        $this->message = $message;
        $this->pdfPath = $pdfPath;
    }

    public function handle()
    {
        Mail::to($this->email)->send(
            (new TransactionMail($this->message))->attach($this->pdfPath)
        );
    }
}
