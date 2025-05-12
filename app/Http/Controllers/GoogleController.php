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
            Log::info('Google User Data:', ['user' => $googleUser]);

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => Hash::make(Str::random(24)),
                ]
            );

            $user->roles()->syncWithoutDetaching(
                [Role::where('name', 'pemohon')->first()?->id]
            );

            Auth::login($user, true);
            $user->load('roles');

            Log::info('User login berhasil dengan role:', ['roles' => $user->roles->pluck('name')]);

            // Ambil source dari session
            $source = session()->pull('login_source');

            return $source === 'qr'
                ? redirect()->route('tracking.surat')
                : redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('Google login error:', ['error' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Something went wrong!');
        }
    }
}
