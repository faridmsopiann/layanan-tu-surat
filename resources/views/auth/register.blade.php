<x-guest-layout>
    <div class="auth-container">
        <!-- KIRI: Ilustrasi & Slogan -->
        <div class="auth-left">
            <img src="{{ asset('images/layanan.gif') }}" alt="Mail Sent" class="auth-image">
            <h2 class="auth-slogan">Satu Portal, Semua Layanan Surat Anda</h2>
        </div>

        <!-- KANAN: Form Register -->
        <div class="auth-right">
            <div class="login-card">
                <div class="login-logo">
                    <img src="{{ asset('images/luin.png') }}" alt="Logo">
                </div>

                <p class="login-title">Daftar untuk memulai</p>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <label for="name" class="form-label">Nama</label>
                    <div class="input-icon">
                        <input id="name" class="form-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <i class="fas fa-user icon"></i>
                    </div>

                    <label for="email" class="form-label">Email</label>
                    <div class="input-icon">
                        <input id="email" class="form-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <i class="fas fa-envelope icon"></i>
                    </div>

                    <label for="password" class="form-label">Password</label>
                    <div class="input-icon">
                        <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
                        <i class="fas fa-lock icon"></i>
                    </div>

                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <div class="input-icon">
                        <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <i class="fas fa-lock icon"></i>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-3">
                            <x-label for="terms">
                                <div class="flex items-center">
                                    <x-checkbox name="terms" id="terms" required />
                                    <div class="ml-2">
                                        {!! __('Saya setuju dengan :terms_of_service dan :privacy_policy', [
                                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Ketentuan Layanan').'</a>',
                                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Kebijakan Privasi').'</a>',
                                        ]) !!}
                                    </div>
                                </div>
                            </x-label>
                        </div>
                    @endif

                    <button type="submit" class="btn-login">
                        <i class="fas fa-user-plus"></i> Daftar
                    </button>

                    <p class="register-text">
                        Sudah terdaftar? <a href="{{ route('login') }}">Masuk</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    
    body {
        font-family: 'Poppins', sans-serif;
    }
    
    .auth-container {
        display: flex;
        min-height: 100vh;
        background-color: #f0f4f8;
    }
    
    .auth-left {
        flex: 1;
        background: linear-gradient(135deg, #a8d0e6, #dbe9f4);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .auth-image {
        max-width: 80%;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }
    
    .auth-slogan {
        font-size: 1.75rem;
        color: #2c3e50;
        font-weight: 600;
        text-align: center;
    }
    
    .auth-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .login-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }
    
    .login-logo img {
        height: 100px;
        margin: 0 auto 1rem;
        display: block;
    }
    
    .login-title {
        font-size: 1.25rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 0.25rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem 2.25rem 0.75rem 0.75rem;
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 1rem;
    }
    
    .input-icon {
        position: relative;
    }
    
    .input-icon i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }
    
    .btn-login {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        gap: 8px;
        padding: 0.75rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        margin-top: 0.5rem;
        cursor: pointer;
        background-color: #2c3e50;
        color: white;
        transition: all 0.2s ease;
    }
    
    .btn-login:hover {
        background-color: #1a3445;
    }

    .register-text {
        text-align: center;
        font-size: 0.9rem;
        margin-top: 1rem;
    }
    
    .register-text a {
        color: #007bff;
        text-decoration: none;
    }
    
    .register-text a:hover {
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .auth-container {
            flex-direction: column;
        }
    
        .auth-left, .auth-right {
            flex: unset;
            width: 100%;
        }
    
        .auth-left {
            padding: 1rem;
        }
    
        .login-card {
            padding: 1.5rem;
            margin: 1rem;
        }
    }
</style>
