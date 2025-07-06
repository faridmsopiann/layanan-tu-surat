<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        app(CreateNewUser::class)->create($request->all());

        return redirect()->route('login')->with('status', 'Pendaftaran akun berhasil. Akun Anda sedang diverifikasi oleh admin.');
    }
}