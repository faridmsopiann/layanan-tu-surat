<x-guest-layout>
    <div class="auth-container">
        <!-- KIRI: Ilustrasi & Slogan -->
        <div class="auth-left">
            <img src="{{ asset('images/layanan.png') }}" alt="Mail Sent" class="auth-image-small">
            <h2 class="auth-slogan">Satu Portal, Semua Layanan Surat Anda</h2>
        </div>

        <!-- KANAN: Form Login -->
        <div class="auth-right">
            <div class="login-card">
                <div class="login-logo">
                    <img src="{{ asset('images/fst.png') }}" alt="Logo">
                </div>

                <p class="login-title">Selamat Datang di Portal Surat FST</p>
                <p class="login-subtitle">Masuk untuk memulai sesi Anda</p>

                <x-validation-errors class="mb-4" />

                @if (session('status'))
                    <div class="alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <label for="email" class="form-label">Email</label>
                    <div class="input-icon">
                        <input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <i class="fas fa-envelope icon"></i>
                    </div>

                    <label for="password" class="form-label">Password</label>
                    <div class="input-icon">
                        <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" />
                        <i class="fas fa-lock icon"></i>
                    </div>

                    <div class="form-extra">
                        <label class="remember-me">
                            <input type="checkbox" name="remember"> Ingat saya
                        </label>

                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">Lupa password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </button>

                    <a href="{{ route('qr.login') }}" class="btn-qr">
                        <i class="fas fa-qrcode"></i> QR Login
                    </a>

                    <a href="{{ route('auth.google') }}" class="btn-google">
                        <img src="https://www.gstatic.com/marketing-cms/assets/images/d5/dc/cfe9ce8b4425b410b49b7f2dd3f3/g.webp=s96-fcrop64=1,00000000ffffffff-rw" alt="Google Logo">
                        Login dengan Google
                    </a>

                    @if (Route::has('register'))
                        <p class="register-text">
                            Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                        </p>
                    @endif
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

    .auth-image-small {
        width: 450px;
        max-width: 100%;
        height: auto;
        margin-top: 10px;
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
    
    .login-subtitle {
        font-size: 0.95rem;
        text-align: center;
        color: #666;
        margin-bottom: 1.5rem;
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
    
    .form-extra {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .remember-me {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .forgot-link {
        color: #007bff;
        text-decoration: none;
    }
    
    .forgot-link:hover {
        text-decoration: underline;
    }
    
    .btn-login, .btn-qr, .btn-google {
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
        transition: all 0.2s ease;
    }
    
    .btn-login {
        background-color: #2c3e50;
        color: white;
    }
    
    .btn-login:hover {
        background-color: #1a3445;
    }
    
    .btn-qr {
        background-color: #004080;
        color: white;
    }
    
    .btn-qr:hover {
        background-color: #003060;
    }
    
    .btn-google {
        background: white;
        border: 1px solid #ddd;
        color: #444;
        gap: 12px;
    }
    
    .btn-google img {
        width: 20px;
        height: 20px;
    }
    
    .btn-google:hover {
        background: #f8f8f8;
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
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 0.75rem;
        border-radius: 8px;
        font-size: 0.95rem;
        margin-bottom: 1rem;
        text-align: center;
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
    