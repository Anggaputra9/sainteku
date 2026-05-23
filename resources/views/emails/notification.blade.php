<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - {{ $app_name ?? config('app.name') }}</title>
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
            padding: 24px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            color: #000;
            font-size: 22px;
            letter-spacing: 0.5px;
        }

        .header p {
            margin: 4px 0 0;
            color: #333;
            font-size: 13px;
        }

        .content {
            padding: 32px 28px;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            background: #111827;
            color: #FEEB04;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .item-card {
            border: 1px solid #eee;
            background: #fafafa;
            padding: 14px 16px;
            margin: 18px 0;
        }

        .item-card .label {
            font-size: 11px;
            text-transform: uppercase;
            color: #888;
            letter-spacing: 1px;
        }

        .item-card .value {
            font-size: 15px;
            font-weight: 600;
            color: #111;
            margin-top: 2px;
        }

        .button {
            display: inline-block;
            background-color: #111827;
            color: #FEEB04 !important;
            text-decoration: none;
            padding: 12px 28px;
            font-weight: 700;
            margin: 18px 0 6px;
            letter-spacing: 0.5px;
        }

        .meta {
            color: #777;
            font-size: 12px;
            margin-top: 24px;
            border-top: 1px dashed #e5e5e5;
            padding-top: 16px;
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
            <h1>{{ $app_name ?? config('app.name') }}</h1>
            <p>Notifikasi Sistem</p>
        </div>

        <div class="content">
            <span class="badge">{{ $type ?? 'Sistem' }}</span>
            <h2 style="margin: 12px 0 8px;">Halo{{ $recipient_name ? ', ' . $recipient_name : '' }} 👋</h2>

            <p style="margin: 0 0 8px;">
                <strong>{{ $sender_name ?? 'Sistem' }}</strong>
                {{ $action ?? 'memberikan informasi terkait' }}:
            </p>

            <div class="item-card">
                <div class="label">{{ $type ?? 'Item' }}</div>
                <div class="value">{{ $item_name ?? '-' }}</div>
            </div>

            @if (!empty($url) && $url !== '#')
                <div style="text-align: center;">
                    <a href="{{ $url }}" class="button">BUKA DI APLIKASI</a>
                </div>
                <p style="font-size: 12px; color: #888; word-break: break-all; margin-top: 4px;">
                    Atau salin tautan: {{ $url }}
                </p>
            @endif

            <div class="meta">
                <p style="margin: 0;">Email ini dikirim otomatis oleh sistem {{ $app_name ?? config('app.name') }}.</p>
                <p style="margin: 4px 0 0;">Mohon jangan membalas email ini.</p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $app_name ?? config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
