<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('images/luin.png') }}" alt="Logo" class="mx-auto" style="height: 120px; width: auto;">
        </x-slot>

        <div class="header">
            <p class="mt-1">
                Masuk untuk memulai sesi Anda                    
            </p>
        </div>

        <!-- Pesan Validasi dan Status -->
        <div class="message-container mb-4">
            <x-validation-errors class="mb-4" />
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 text-center">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <div class="input-icon">
                    <input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <i class="fas fa-envelope icon"></i> <!-- Ikon email -->
                </div>
            </div>

            <div class="form-group mt-2 mb-4">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <div class="input-icon">
                    <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" />
                    <i class="fas fa-lock icon"></i> <!-- Ikon password -->
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember" class="checkbox" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div>

            <div class="submit-button">
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> <!-- Ikon untuk tombol login -->
                    {{ __('Masuk') }}
                </button>
            </div>

            <!-- Tombol QR Login -->
            <div class="mt-4 text-center">
                <a href="{{ route('qr.login') }}" class="btn-qr-login">
                    <i class="fas fa-qrcode"></i> QR Login
                </a>
            </div>

            <div class="mt-4 border-t pt-4 text-center">
                @if (Route::has('register'))
                    <p class="text-sm text-gray-600">
                        {{ __('Belum punya akun?') }}
                        <a class="register-link" href="{{ route('register') }}">
                            {{ __('Daftar') }}
                        </a>
                    </p>
                @endif
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

<style>
    /* General Styles */
    body {
        background-color: #f0f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    /* Header Styles */
    .header {
        background-color: #2C3E50;
        padding: 15px 0;
        text-align: center;
        border-radius: 10px 10px 0 0;
        color: white;
        margin: -1.5rem -1.5rem 1rem -1.5rem;
    }

    /* Form Styles */
    .login-form {
        background-color: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        width: 350px;
        margin: 0 auto;
    }

    .form-label {
        display: block;
        font-weight: bold;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 2.0rem 0.75rem 0.75rem; /* Menambah padding kanan untuk ikon */
        margin-top: 0.25rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        transition: border-color 0.2s;
        font-size: 1rem;
    }

    .form-input:focus {
        border-color: #17a2b8;
        outline: none;
    }

    .input-icon {
        position: relative;
    }

    .input-icon i {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 1rem;
    }

    .checkbox {
        margin-right: 0.5rem;
    }

    .forgot-password {
        color: #007bff;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .forgot-password:hover {
        text-decoration: underline;
    }

    .submit-button {
        margin-top: 1.5rem;
    }

    .btn-login {
        width: 100%;
        padding: 0.75rem;
        background-color: #2C3E50;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-login i {
        margin-right: 0.5rem;
    }

    .btn-login:hover {
        background-color: #138496;
    }

    .btn-qr-login {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background-color: #2C3E50;
        color: white;
        border-radius: 5px;
        font-size: 1rem;
        text-decoration: none;
        margin-top: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
    }

    .btn-qr-login i {
        margin-right: 0.5rem;
    }

    .btn-qr-login:hover {
        background-color: #138496;
    }

    .register-link {
        color: #007bff;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .register-link:hover {
        text-decoration: underline;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
