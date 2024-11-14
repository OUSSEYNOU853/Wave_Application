<?php

namespace App\Listeners;

use App\Enums\RoleEnum;
use App\Events\SendEmailEvent;
use App\Jobs\SendEmailJob;
use App\Services\PdfService;
use App\Services\QrCodeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendEmailListener implements ShouldQueue
{
    protected $pdfService;
    protected $qrCodeService;

    public function __construct(PdfService $pdfService, QrCodeService $qrCodeService)
    {
        $this->pdfService = $pdfService;
        $this->qrCodeService = $qrCodeService;
    }

    public function handle(SendEmailEvent $event)
    {
        $qrCodePath = storage_path('app/public/qrcodes/' . $event->user->id . '_qrcode.png');
        $pdfPath = storage_path('app/public/pdfs/' . $event->user->id . '_card.pdf');

        Storage::makeDirectory('public/qrcodes');
        Storage::makeDirectory('public/pdfs');

        $qrCodeData = json_encode([
            'id' => $event->user->id,
            'lastname' => $event->user->lastname,
            'firstname' => $event->user->firstname,
            'email' => $event->user->email,
            'phone' => $event->user->phone,
            'address' => $event->user->address,
            'gender' => $event->user->gender,
            'cni' => $event->user->cni,
            'photo' => $event->user->photo
        ]);

        $this->qrCodeService->generateQrCode($qrCodeData, $qrCodePath);

        $data = [
            'user' => $event->user,
            'profile_image' => $event->user->photo
        ];

        $this->pdfService->generatePdfWithQrCode($data, $qrCodePath, $pdfPath);

        $message = [
            'title' => 'Bienvenue ' . $event->user->firstname . ' ' . $event->user->lastname . ' !',
            'body' => "Votre compte a été créé avec succès."
        ];

        // Vérifier le rôle de l'utilisateur
        switch ($event->user->role) {
            case RoleEnum::CLIENT->value:
                case RoleEnum::MARCHAND->value:
                    $message['body'] .= " Veillez cliquez sur ce lien afin  \n";
                SendEmailJob::dispatch($event->user->email, $message, $pdfPath);
                break;
            case RoleEnum::DISTRIBUTEUR->value:
                $message['body'] .= " Vos identifiants de connexion sont : \n";
                $message['body'] .= "Mot de passe : " . $event->user->password . "\n";
                $message['body'] .= "Code secret : " . $event->user->secret_code . "\n";
                SendEmailJob::dispatch($event->user->email, $message, null);
                break;
        }
    }
}