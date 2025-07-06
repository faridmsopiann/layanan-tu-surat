<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        if ($request->get('from') === 'qr') {
            session(['login_source' => 'qr']);
        }

        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'status' => 'inactive', 
                ]);

                $user->roles()->syncWithoutDetaching(
                    [Role::where('name', 'pemohon')->first()?->id]
                );

                return redirect()->route('login')
                    ->with('status', 'Pendaftaran akun berhasil. Akun Anda sedang diverifikasi oleh admin.');
            }

            if ($user->status !== 'active') {
                return redirect()->route('login')
                    ->with('status', 'Akun Anda belum aktif. Tunggu verifikasi admin.');
            }

            Auth::login($user, true);

            $source = session()->pull('login_source');

            return $source === 'qr'
                ? redirect()->route('tracking.surat')
                : redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Something went wrong!');
        }
    }
}
