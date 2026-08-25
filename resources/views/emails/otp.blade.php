<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email Nexavira</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        <h2 style="color: #2c3e50;">Verifikasi Email Anda</h2>
        <p>Halo <strong>{{ $name }}</strong>,</p>
        <p>Terima kasih telah mendaftar di portal Nexavira. Silakan gunakan kode OTP di bawah ini untuk memverifikasi alamat email Anda.</p>
        <div style="background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 24px; letter-spacing: 5px; font-weight: bold; border-radius: 5px; margin: 20px 0;">
            {{ $otp_code }}
        </div>
        <p style="color: #e74c3c; font-size: 14px;">Kode OTP ini hanya berlaku selama 10 menit. Mohon untuk tidak membagikan kode ini kepada siapapun.</p>
        <hr style="border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 12px; color: #7f8c8d;">Jika Anda tidak merasa melakukan pendaftaran ini, silakan abaikan email ini.</p>
        <p style="font-size: 12px; color: #7f8c8d;">Salam hangat,<br>Tim Nexavira</p>
    </div>
</body>
</html>
