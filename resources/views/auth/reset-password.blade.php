<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Password - {{ config('app.name') }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #e2e8f0; /* Menyesuaikan background admin global */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1f2937;
        }
        .container {
            width: 100%;
            max-width: 440px;
            background-color: #ffffff; /* Card putih seperti di admin */
            border: 1px solid #cbd5e1; /* Border abu-abu seperti di admin */
            border-radius: 16px;
            padding: 40px 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .logo {
            text-align: center;
            font-size: 26px;
            font-weight: 800;
            color: #0d9488; /* Teal brand color */
            margin-bottom: 24px;
            letter-spacing: -0.03em;
        }
        .header {
            text-align: center;
            margin-bottom: 28px;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 6px;
            letter-spacing: -0.01em;
        }
        .header p {
            font-size: 14px;
            color: #6b7280;
        }
        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }
        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            text-align: center;
            font-weight: 500;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            background-color: #ffffff;
            border: 1px solid #cbd5e1; /* Input border */
            border-radius: 8px;
            padding: 10px 14px;
            color: #1f2937;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #0d9488; /* Teal border pada focus */
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.25);
        }
        .btn {
            width: 100%;
            background-color: #0d9488; /* Teal solid seperti di admin */
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            display: inline-block;
            text-align: center;
            text-decoration: none;
            margin-top: 8px;
        }
        .btn:hover {
            background-color: #0f766e; /* Hover teal lebih gelap */
        }
        .btn:active {
            transform: scale(0.98);
        }
        .footer-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
        }
        .footer-link a {
            color: #0d9488;
            text-decoration: none;
            font-weight: 600;
        }
        .footer-link a:hover {
            color: #0f766e;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            {{ config('app.name') }}
        </div>
        
        @if (session('success'))
            <div class="header">
                <h1>Sukses!</h1>
                <p>Password Anda telah berhasil diperbarui.</p>
            </div>
            
            <div class="alert alert-success">
                Password admin Anda sekarang telah aktif. Silakan gunakan password baru untuk masuk ke sistem.
            </div>
            
            <a href="{{ url('/admin/login') }}" class="btn">Masuk Ke Dashboard</a>
        @else
            <div class="header">
                <h1>Setup Password</h1>
                <p>Atur password untuk mengakses dashboard admin Anda</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="padding-left: 16px; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/admin/password-reset') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Minimal 8 karakter" autofocus>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn">Simpan Password & Aktifkan</button>
            </form>
        @endif
    </div>
</body>
</html>