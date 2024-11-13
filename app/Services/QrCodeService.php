<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateQrCode($data, $path)
    {
        QrCode::format('png')->size(300)->generate($data, $path);
    }
}
