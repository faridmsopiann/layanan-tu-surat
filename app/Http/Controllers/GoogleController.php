<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Mendapatkan informasi pengguna dari Google
            $googleUser = Socialite::driver('google')->user();

            // Log data user
            Log::info('Google User Data:', ['user' => $googleUser]);

            // Cek apakah user sudah ada
            $user = User::where('email', $googleUser->getEmail())->first();

            // Log hasil pencarian user
            Log::info('User ditemukan:', ['user' => $user]);

            // Jika user belum ada, buat user baru
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'pemohon',
                ]);
                Log::info('User baru dibuat:', ['user' => $user]);
            }

            // Login pengguna
            Auth::login($user, true);
            Log::info('User login berhasil', ['user' => $user]);

            // Redirect ke dashboard
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            // Log error
            Log::error('Google login error:', ['error' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Something went wrong!');
        }
    }
}
