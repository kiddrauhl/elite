<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
    <div style="width: 100%; background-color: #f8fafc; padding: 40px 20px; text-align: center;">
        
        <!-- Main Card -->
        <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 24px; padding: 40px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; text-align: center;">
            
            <!-- Header/Logo Text -->
            <h2 style="color: #0f172a; margin-top: 0; font-size: 24px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                Elite English Course
            </h2>
            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">

            <!-- Greeting & Context -->
            <h3 style="color: #0f172a; font-size: 20px; margin-bottom: 10px;">Halo Pendaftar!</h3>
            <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 30px;">
                Terima kasih telah mendaftar. Untuk menyelesaikan proses registrasi dan mengaktifkan akun Anda, silakan masukkan kode verifikasi berikut:
            </p>

            <!-- OTP Box -->
            <div style="background-color: #fffbeb; border: 2px dashed #f59e0b; border-radius: 16px; padding: 20px; margin-bottom: 30px;">
                <h1 style="color: #d97706; font-size: 42px; letter-spacing: 10px; margin: 0; font-weight: 900;">
                    {{ $otpCode }}
                </h1>
            </div>

            <!-- Warning Note -->
            <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 12px; margin-bottom: 20px; text-align: left; border-radius: 4px;">
                <p style="color: #b91c1c; font-size: 13px; font-weight: bold; margin: 0;">
                    Jaga Keamanan Akun Anda
                </p>
                <p style="color: #7f1d1d; font-size: 13px; margin: 5px 0 0 0; line-height: 1.4;">
                    Harap <b>jangan bagikan</b> kode OTP ini kepada siapa pun, termasuk pihak yang mengatasnamakan Elite English.
                </p>
            </div>

            <p style="color: #94a3b8; font-size: 13px; line-height: 1.5; margin-bottom: 0;">
                Kode ini dikirimkan secara otomatis. Jika Anda tidak merasa melakukan pendaftaran, silakan abaikan email ini.
            </p>
        </div>

        <!-- Footer -->
        <p style="color: #94a3b8; font-size: 12px; margin-top: 24px;">
            &copy; {{ date('Y') }} EliteEnglishCourse. All rights reserved.
        </p>

    </div>
</body>
</html>