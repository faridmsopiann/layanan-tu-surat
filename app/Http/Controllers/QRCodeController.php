<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function generateLoginQR()
    {
        $googleLoginUrl = route('auth.google', ['from' => 'qr']);

        $qrCode = QrCode::size(300)->generate($googleLoginUrl);

        return view('auth.qr-login', compact('qrCode'));
    }
}
