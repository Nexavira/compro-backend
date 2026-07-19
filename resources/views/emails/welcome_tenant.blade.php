<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di {{ config('app.name') }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            font-family: 'Plus Jakarta Sans', 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            color: #334155;
        }
        table {
            border-spacing: 0;
            width: 100%;
        }
        td {
            padding: 0;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f0f2f5;
            padding: 40px 0;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            padding: 56px 32px;
            text-align: center;
            position: relative;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }
        .header p {
            color: #c7d2fe;
            margin: 10px 0 0 0;
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .content {
            padding: 48px 40px;
            background-color: #ffffff;
        }
        .content h2 {
            color: #0f172a;
            font-size: 24px;
            font-weight: 800;
            margin-top: 0;
            margin-bottom: 24px;
            letter-spacing: -0.02em;
        }
        .badge {
            display: inline-block;
            background-color: #ecfdf5;
            color: #059669;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 99px;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid #a7f3d0;
        }
        .badge-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            margin-right: 6px;
        }
        .message-card {
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 28px;
        }
        .message-card p {
            margin: 0;
            font-size: 16px;
            line-height: 1.6;
            color: #334155;
        }
        .content p.instructions {
            font-size: 16px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 32px;
        }
        .cta-container {
            text-align: center;
            margin: 40px 0;
        }
        .btn {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 18px 40px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 14px;
            display: inline-block;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3), 0 4px 6px -4px rgba(79, 70, 229, 0.3);
            letter-spacing: -0.01em;
            transition: all 0.3s ease;
        }
        .btn:hover {
            box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.4), 0 8px 10px -6px rgba(79, 70, 229, 0.4);
            transform: translateY(-2px);
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 40px 0;
        }
        .signature {
            font-size: 15px;
            color: #64748b;
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            padding: 0 40px 48px 40px;
            background-color: #ffffff;
        }
        .footer p {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.6;
            margin: 0 0 10px 0;
        }
        .footer a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <center class="wrapper">
        <table class="main" width="100%">
            <tr>
                <td class="header">
                    <h1>{{ config('app.name') }}</h1>
                    <p>Setup Akun Sistem Anda</p>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <div class="badge">
                        <span class="badge-dot"></span>Layanan Aktif
                    </div>
                    <h2>Halo, {{ $user->name ?? 'Admin' }}!</h2>
                    <div class="message-card">
                        <p>Selamat! Pembayaran untuk company profile {{ $tenant->name ?? 'Tenant' }} telah berhasil dikonfirmasi. Layanan Anda kini telah aktif dan dapat digunakan.</p>
                    </div>
                    <p class="instructions">Untuk langkah pertama, silakan klik tombol di bawah ini untuk mengatur password akun admin Anda.</p>
                    <div class="cta-container">
                        <a href="{{ $resetUrl ?? '#' }}" class="btn" target="_blank">Setup Password Anda</a>
                    </div>
                    <p class="instructions">Jika Anda mengalami kesulitan, silakan hubungi tim dukungan kami.</p>
                    <p class="instructions" style="font-weight: 600; color: #4f46e5; margin-top: 24px;">Selamat mencoba pengalaman yang menakjubkan bersama Nexavira!</p>
                    <div class="divider"></div>
                    <div class="signature">
                        Terima kasih,<br>
                        <strong>{{ config('app.name') }}</strong>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    <p>
                        Butuh bantuan? Hubungi tim support kami melalui sistem tiket atau email.
                    </p>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>