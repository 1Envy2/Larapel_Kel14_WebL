<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Anda</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4;">

    <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333;">Verifikasi Email Anda</h2>
        
        <p>Halo,</p>
        
        <p>Anda telah meminta Kode Verifikasi Satu Kali (OTP) untuk akun Anda. Silakan gunakan kode di bawah ini untuk melanjutkan pendaftaran Anda.</p>
        
        <div style="background-color: #e6f7ff; border: 1px solid #91d5ff; padding: 15px; text-align: center; margin: 20px 0;">
            <p style="font-size: 24px; font-weight: bold; color: #1890ff; margin: 0;">
                {{ $otpCode }} 
            </p>
        </div>
        
        <p style="color: #666; font-size: 12px;">Kode ini akan kedaluwarsa dalam 5 menit. Jangan bagikan kode ini kepada siapapun.</p>
        
        <p>Terima kasih,<br>Tim {{ config('app.name') }}</p>
    </div>

</body>
</html>