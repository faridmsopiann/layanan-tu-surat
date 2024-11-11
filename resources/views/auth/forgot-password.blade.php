<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img src="{{ asset('images/luin.png') }}" alt="Logo" class="mx-auto" style="height: 120px; width: auto;">
        </x-slot>

        <!-- Header for Reset Password -->
        <!-- Header dengan ikon dan teks -->
        <div class="header flex items-center justify-center mb-6">
            <i class="fas fa-lock text-white text-3xl mr-2 mt-1"></i>
            <p class="mt-1">Atur Ulang Kata Sandi</p>
        </div>

        <div class="notification-box text-sm text-gray-800 text-justify">
            {{ __('Jika Anda lupa kata sandi, silakan masukkan alamat email Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi. Tautan tersebut memungkinkan Anda untuk membuat kata sandi baru dan mengakses akun Anda kembali.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <div class="input-icon">
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <i class="fas fa-envelope icon"></i> <!-- Email Icon -->
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="relative">
                    <i class="fas fa-paper-plane icon-button"></i> <!-- Button Icon -->
                    {{ __('Email Password Reset Link') }}
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

    .notification-box {
        background-color: #f9f9f9;
        border-left: 4px solid #17a2b8;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 1rem;
    }

    .notification-box .text-sm {
        font-size: 0.875rem;
    }

    .notification-box .text-gray-800 {
        color: #2d3748;
    }

    .text-justify {
        text-align: justify;
    }

    /* Input Icon Styles */
    .input-icon {
        position: relative;
    }

    .input-icon i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
        font-size: 1rem;
    }

    /* Button Icon Styles */
    .icon-button {
        margin-right: 0.5rem;
        color: white;
        font-size: 1rem;
    }

    .relative {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding-left: 1.5rem;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 640px) {
        .header {
            padding: 10px 0;
            margin: -1rem -1rem 1rem -1rem;
            font-size: 1rem;
        }

        .notification-box {
            padding: 10px;
            font-size: 0.8rem;
        }

        .input-icon i, .icon-button {
            font-size: 0.9rem;
            right: 10px;
        }

        .relative {
            padding-left: 1.25rem;
        }

        .block {
            margin-bottom: 1rem;
        }
    }
</style>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
