<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sainteku</title>
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
            border-radius: 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(145deg, #FEEB04 0%, #CBB800 100%);
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            color: #000000;
            font-size: 28px;
        }

        .header p {
            margin: 5px 0 0;
            color: #333;
        }

        .content {
            padding: 40px 30px;
        }

        .button {
            display: inline-block;
            background-color: #FEEB04;
            color: #000000;
            text-decoration: none;
            padding: 12px 30px;
            font-weight: bold;
            margin: 20px 0;
            border: none;
        }

        .button:hover {
            background-color: #CBB800;
        }

        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }

        .warning {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Sainteku</h1>
            <p>Fakultas Sains & Teknologi</p>
            <p>UIN Prof. K.H. Saifuddin Zuhri Purwokerto</p>
        </div>

        <div class="content">
            <h2 style="margin-top: 0;">Reset Password</h2>

            <p>Halo,</p>

            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda dengan email <strong><?php echo e($email); ?></strong>.</p>

            <div style="text-align: center;">
                <a href="<?php echo e($resetLink); ?>" class="button">RESET PASSWORD SEKARANG</a>
            </div>

            <p class="warning">Link ini akan kadaluarsa dalam <strong>60 menit</strong>.</p>

            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>

            <hr style="border: 1px solid #eee; margin: 30px 0;">

            <p style="font-size: 14px;">Jika tombol di atas tidak berfungsi, copy dan paste link berikut ke browser Anda:</p>
            <p style="font-size: 12px; word-break: break-all; background: #f5f5f5; padding: 10px;"><?php echo e($resetLink); ?></p>
        </div>

        <div class="footer">
            <p>&copy; <?php echo e(date('Y')); ?> Sainteku. All rights reserved.</p>
            <p>Jl. A. Yani No. 40-A, Purwokerto, Jawa Tengah 53126</p>
        </div>
    </div>
</body>

</html><?php /**PATH D:\Unduhan\sainteku\resources\views/emails/reset-password.blade.php ENDPATH**/ ?>