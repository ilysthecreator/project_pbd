<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem POS - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0056b3 0%, #007bff 100%); /* Warna latar belakang modern */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #2c3e50;
        }

        .login-card {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-card h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }

        .login-card p.subtitle {
            color: #64748b;
            margin-bottom: 2rem;
            font-size: 0.9375rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            color: #34495e;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem 1.5rem;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-login:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 0.75rem;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: left;
            font-size: 0.875rem;
        }
        
        .invalid-feedback {
            color: #e3342f;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
        }

        .footer-text {
            margin-top: 2rem;
            font-size: 0.75rem;
            color: #a0a0a0;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Masuk ke Sistem POS</h1>
        <p class="subtitle">Silakan masukkan detail akun Anda untuk melanjutkan.</p>

        {{-- Menampilkan pesan error atau sukses dari sesi --}}
        @if (session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert-error" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif
        
        {{-- Formulir Login --}}
        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            {{-- Input Username --}}
            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Input Password --}}
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Tombol Login --}}
            <div class="form-group">
                <button type="submit" class="btn-login">
                    Masuk
                </button>
            </div>
        </form>

        <div class="footer-text">
            &copy; 2025 Sistem Point of Sale
        </div>
    </div>
</body>
</html>