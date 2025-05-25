
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Portal Surat FST</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            overflow-x: hidden;
        }

        .auth-container {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, #e6f0fa 0%, #d1e3f6 100%);
            overflow: hidden;
        }

        /* Background floating circles */
        .auth-container::before,
        .auth-container::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(74, 144, 226, 0.15);
            animation: float 15s infinite ease-in-out;
        }

        .auth-container::before {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 15%;
            opacity: 0.7;
        }

        .auth-container::after {
            width: 150px;
            height: 150px;
            bottom: 20%;
            right: 10%;
            opacity: 0.5;
            animation-delay: 5s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-30px);
            }
        }

        .login-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            border: 1px solid transparent;
            background: linear-gradient(#ffffff, #ffffff) padding-box,
                        linear-gradient(135deg, #4a90e2, #d1e3f6) border-box;
            animation: fadeInScale 0.8s ease-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .login-card:hover {
            transform: translateY(-3px);
        }

        .login-logo img {
            height: 70px;
            margin: 0 auto 1.2rem;
            display: block;
        }

        .login-title {
            font-size: 1.4rem;
            font-weight: 600;
            text-align: center;
            color: #1a202c;
            margin-bottom: 0.4rem;
        }

        .login-subtitle {
            font-size: 0.9rem;
            text-align: center;
            color: #718096;
            margin-bottom: 1.2rem;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 0.4rem;
            display: block;
        }

        .input-icon {
            position: relative;
            margin-bottom: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 0.7rem 2.2rem 0.7rem 0.9rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.1);
        }

        .input-icon i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 0.9rem;
        }

        .form-extra {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            gap: 6px;
            color: #4a5568;
        }

        .form-extra a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
        }

        .form-extra a:hover {
            text-decoration: underline;
        }

        .btn-login {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.7rem;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            margin-bottom: 0.8rem;
            background: #4a90e2;
            color: #ffffff;
        }

        .btn-login:hover {
            animation: pulse 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            background: #357abd;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.08);
            }
            100% {
                transform: scale(1);
            }
        }

        .register-text {
            text-align: center;
            font-size: 0.85rem;
            color: #718096;
            margin-top: 1rem;
        }

        .register-text a {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
        }

        .register-text a:hover {
            text-decoration: underline;
        }

        .alert-success {
            background: #e6fffa;
            color: #2e7d32;
            border: 1px solid #b2f5ea;
            padding: 0.6rem;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .error_message {
            background: #fff5f5;
            color: #c53030;
            border: 1px solid #feb2b2;
            padding: 0.6rem;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            text-align: center;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        @media (max-width: 768px) {
            .login-card {
                padding: 1.5rem;
                max-width: 90%;
            }

            .login-logo img {
                height: 60px;
            }

            .auth-container::before,
            .auth-container::after {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>
<body>
    <x-guest-layout>
        <div class="auth-container">
            <!-- Form Register -->
            <div class="login-card">
                <div class="login-logo">
                    <img src="{{ asset('images/fst.png') }}" alt="Logo">
                </div>

                <p class="login-title">Daftar untuk memulai</p>
                <p class="login-subtitle">Buat akun Anda sekarang</p>

                <x-validation-errors class="error_message" />

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
                        <div class="form-extra">
                            <input type="checkbox" name="terms" id="terms" required />
                            <label for="terms">
                                {!! __('Saya setuju dengan :terms_of_service dan :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'">'.__('Ketentuan Layanan').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'">'.__('Kebijakan Privasi').'</a>',
                                ]) !!}
                            </label>
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
    </x-guest-layout>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const errorBox = document.querySelector('.error_message');

            if (errorBox && errorBox.textContent.trim()) {
                setTimeout(() => {
                    errorBox.style.transition = 'opacity 0.5s ease';
                    errorBox.style.opacity = '0';
                    setTimeout(() => {
                        errorBox.style.display = 'none';
                    }, 500);
                }, 3000);
            }
        });
    </script>
</body>
</html>
