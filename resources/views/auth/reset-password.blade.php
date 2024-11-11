<x-guest-layout>
    <x-authentication-card class="max-w-sm mx-auto">
        <x-slot name="logo">
            <img src="{{ asset('images/luin.png') }}" alt="Logo" class="mx-auto" style="height: 120px; width: auto;">
        </x-slot>

        <!-- Header dengan ikon dan teks -->
        <div class="header flex items-center justify-center mb-6">
            <i class="fas fa-lock text-white text-3xl mr-2 mt-1"></i>
            <p class="mt-1">Atur Ulang Kata Sandi</p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block relative">
                <x-label for="email" value="{{ __('Email') }}" />
                <div class="input-icon">
                    <x-input id="email" class="block mt-1 w-full pl-10" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <i class="fas fa-envelope icon-input"></i> <!-- Ikon Email -->
                </div>
            </div>

            <div class="mt-4 relative">
                <x-label for="password" value="{{ __('Password') }}" />
                <div class="input-icon">
                    <x-input id="password" class="block mt-1 w-full pl-10" type="password" name="password" required autocomplete="new-password" />
                    <i class="fas fa-key icon-input"></i> <!-- Ikon Password -->
                </div>
            </div>

            <div class="mt-4 relative">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <div class="input-icon">
                    <x-input id="password_confirmation" class="block mt-1 w-full pl-10" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <i class="fas fa-check-circle icon-input"></i> <!-- Ikon Confirm Password -->
                </div>
            </div>

            <div class="flex items-center justify-center mt-6">
                <x-button class="w-full flex items-center justify-center space-x-2">
                    <i class="fas fa-paper-plane text-white"></i> <!-- Ikon tombol -->
                    <span>{{ __('Reset Password') }}</span>
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

<style>
    /* Header Styles */
    .header {
        background-color: #2C3E50;
        padding: 15px 0;
        text-align: center;
        border-radius: 10px 10px 0 0;
        color: white;
        margin: -1.5rem -1.5rem 1rem -1.5rem;
    }

    /* Input Icon Styles */
    .input-icon {
        position: relative;
    }

    .input-icon i.icon-input {
        position: absolute;
        left: 12px; /* Increase left padding for better spacing */
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 1rem;
    }

    /* Padding for input fields to accommodate icons */
    .pl-10 {
        padding-left: 2.75rem; /* Slightly increase padding for icon space */
    }

    /* Button Styles */
    .icon-button {
        margin-right: 0.5rem; /* Add space between icon and text */
        color: white;
        font-size: 1rem;
    }

    /* Full width button styling */
    .w-full {
        width: 100%;
    }

    .space-x-2 > *:not(:last-child) {
        margin-right: 0.5rem; /* Space between icon and text in button */
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .header {
            padding: 10px 0;
            margin: -1rem -1rem 1rem -1rem;
            font-size: 1rem;
        }

        .input-icon i.icon-input, .icon-button {
            font-size: 0.9rem;
            left: 10px;
        }

        .pl-10 {
            padding-left: 2.5rem; /* Adjust for smaller screens */
        }

        .relative {
            padding-left: 1.25rem;
        }
    }
</style>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
