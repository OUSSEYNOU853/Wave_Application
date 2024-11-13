<?php

namespace App\Listeners;

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

    public function handle(SendEmailEvent $event)  // Changé ici pour correspondre au nouvel événement
    {
        // Préparer les chemins pour les fichiers
        $qrCodePath = storage_path('app/public/qrcodes/' . $event->user->id . '_qrcode.png');
        $pdfPath = storage_path('app/public/pdfs/' . $event->user->id . '_card.pdf');

        // Créer les dossiers s'ils n'existent pas
        Storage::makeDirectory('public/qrcodes');
        Storage::makeDirectory('public/pdfs');

        // Générer le QR code avec les données de l'utilisateur
        $qrCodeData = json_encode([
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

        // Préparer les données pour le PDF
        $data = [
            'user' => $event->user,
            'profile_image' => $event->user->photo
        ];

        // Générer le PDF avec le QR code
        $this->pdfService->generatePdfWithQrCode($data, $qrCodePath, $pdfPath);
        
        // Préparer le message de bienvenue
        $message = [
            'title' => 'Bienvenue ' . $event->user->firstname . ' ' . $event->user->lastname . ' !',
            'body' => "Votre compte a été créé avec succès. Vous trouverez ci-joint votre carte d'identité numérique."
        ];

        // Dispatcher le job d'envoi d'email
        SendEmailJob::dispatch($event->user->email, $message, $pdfPath);
    }
}
