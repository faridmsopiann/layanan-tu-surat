<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function generateLoginQR()
    {
        // Buat URL login Google dengan tambahan 'state=qr'
        $googleLoginUrl = Socialite::driver('google')
            ->with(['state' => 'qr']) // untuk menandai bahwa ini login via QR
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        // Generate QR code dengan URL di atas
        $qrCode = QrCode::size(300)->generate($googleLoginUrl);

        return view('auth.qr-login', compact('qrCode'));
    }
}
