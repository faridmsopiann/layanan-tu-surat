<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function generateLoginQR()
    {
        $googleLoginUrl = route('auth.google');
        $qrCode = QrCode::size(300)->generate($googleLoginUrl);

        return view('auth.qr-login', compact('qrCode'));
    }
}
