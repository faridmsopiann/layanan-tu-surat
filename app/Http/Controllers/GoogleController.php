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
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            Log::info('Google User Data:', ['user' => $googleUser]);

            // Cek apakah user sudah ada
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                ]);

                // Tambahkan role ke pivot table
                $pemohonRole = Role::where('name', 'pemohon')->first();
                if ($pemohonRole) {
                    $user->roles()->attach($pemohonRole);
                }
                Log::info('User baru dibuat dan diberikan role pemohon', ['user' => $user]);
            }

            // Login user
            Auth::login($user, true);
            $user->load('roles'); // Refresh relasi roles
            Log::info('User login berhasil dengan role:', ['roles' => $user->roles->pluck('name')]);

            // Redirect ke dashboard
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('Google login error:', ['error' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Something went wrong!');
        }
    }
}
