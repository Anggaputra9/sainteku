<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(145deg, #FEEB04 0%, #CBB800 100%);
            padding: 28px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            color: #000;
            font-size: 26px;
        }

        .header p {
            margin: 4px 0 0;
            color: #333;
            font-size: 13px;
        }

        .content {
            padding: 36px 30px;
        }

        .button {
            display: inline-block;
            background-color: #111827;
            color: #FEEB04 !important;
            text-decoration: none;
            padding: 14px 32px;
            font-weight: 700;
            margin: 20px 0;
            letter-spacing: 0.5px;
        }

        .footer {
            background-color: #f9f9f9;
            padding: 18px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Verifikasi Alamat Email</p>
        </div>

        <div class="content">
            <h2 style="margin-top: 0;">Halo{{ $name ? ', ' . $name : '' }} 👋</h2>

            <p>Terima kasih sudah terdaftar di sistem <strong>{{ config('app.name') }}</strong>.
                Sebelum mulai menggunakan akun, mohon konfirmasi dulu bahwa email
                <strong>{{ $email }}</strong> ini benar milik Anda.
            </p>

            <div style="text-align: center;">
                <a href="{{ $verifyLink }}" class="button">VERIFIKASI EMAIL SEKARANG</a>
            </div>

            <p style="color: #666; font-size: 14px;">
                Tautan ini akan kadaluarsa dalam <strong>60 menit</strong>.
                Jika Anda tidak merasa membuat akun di sistem kami, Anda boleh mengabaikan email ini.
            </p>

            <hr style="border: 1px solid #eee; margin: 28px 0;">

            <p style="font-size: 13px;">Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser:
            </p>
            <p style="font-size: 12px; word-break: break-all; background: #f5f5f5; padding: 10px;">
                {{ $verifyLink }}
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
