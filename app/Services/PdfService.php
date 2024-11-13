<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    public function generatePdfWithQrCode($data, $qrCodePath, $pdfPath)
    {
        $pdf = Pdf::loadView('pdfs.user_card', compact('data', 'qrCodePath'));
        $pdf->save($pdfPath);
    }
}
