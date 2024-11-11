<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('images/luin.png') }}" alt="Logo" class="mx-auto" style="height: 120px; width: auto;">
        </x-slot>

        <x-validation-errors class="mb-4" />

        <div class="header">
            <p class="mt-1">
                Daftar untuk memulai
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="register-form">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <div class="input-icon">
                    <input id="name" class="form-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <i class="fas fa-user icon"></i>
                </div>
            </div>

            <div class="form-group mt-2">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <div class="input-icon">
                    <input id="email" class="form-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <i class="fas fa-envelope icon"></i>
                </div>
            </div>

            <div class="form-group mt-2">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <div class="input-icon">
                    <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
                    <i class="fas fa-lock icon"></i>
                </div>
            </div>

            <div class="form-group mt-2">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <div class="input-icon">
                    <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <i class="fas fa-lock icon"></i>
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-3">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />
                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="submit-button">
                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i>
                    {{ __('Daftar') }}
                </button>
            </div>

            <div class="mt-3 text-center">
                <p class="text-sm text-gray-600">
                    {{ __('Sudah terdaftar?') }}
                    <a class="login-link" href="{{ route('login') }}">
                        {{ __('Masuk') }}
                    </a>
                </p>
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
    .register-form {
        background-color: white;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        width: 300px;
        margin: 0 auto;
    }

    .form-label {
        display: block;
        font-weight: bold;
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 2.0rem 0.75rem 0.75rem;
        margin-top: 0.25rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        transition: border-color 0.2s;
        font-size: 0.9rem;
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
        font-size: 0.9rem;
    }

    .submit-button {
        margin-top: 1rem;
    }

    .btn-register {
        width: 100%;
        padding: 0.5rem;
        background-color: #2C3E50;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-register i {
        margin-right: 0.5rem;
    }

    .btn-register:hover {
        background-color: #138496;
    }

    .login-link {
        color: #007bff;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .login-link:hover {
        text-decoration: underline;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
