<?php

namespace App\Support;

class PdfImageHelper
{
    public static function uinPrintLogoDataUri(): string
    {
        $path = self::resolveUinPrintLogoPath();
        if ($path === '') {
            return '';
        }

        // File cetak sudah di-downscale dari PNG asli — hindari resize ulang biar tidak blur.
        return self::rawFileDataUri($path) ?? '';
    }

    public static function regenerateUinPrintLogo(int $size = 180, int $quality = 95): bool
    {
        $source = public_path('assets/images/uin.png');
        $dest = public_path('assets/images/uin-print.jpg');

        if (! is_file($source) || ! function_exists('imagecreatefrompng') || ! function_exists('imagejpeg')) {
            return false;
        }

        $src = @imagecreatefrompng($source);
        if ($src === false) {
            return false;
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);
        $dst = imagecreatetruecolor($size, $size);

        if ($dst === false) {
            imagedestroy($src);

            return false;
        }

        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        imagealphablending($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $size, $size, $srcW, $srcH);

        $result = imagejpeg($dst, $dest, $quality);

        imagedestroy($src);
        imagedestroy($dst);

        return $result;
    }

    public static function rawFileDataUri(string $absolutePath): ?string
    {
        if (! is_file($absolutePath)) {
            return null;
        }

        $binary = file_get_contents($absolutePath);
        if ($binary === false || $binary === '') {
            return null;
        }

        $imageInfo = @getimagesize($absolutePath);
        $mime = $imageInfo['mime'] ?? mime_content_type($absolutePath) ?: 'image/jpeg';

        return 'data:' . $mime . ';base64,' . base64_encode($binary);
    }

    public static function resolveUinPrintLogoPath(): string
    {
        $optimizedLogo = public_path('assets/images/uin-print.jpg');
        $sourceLogo = public_path('assets/images/uin.png');

        if (! is_file($sourceLogo)) {
            return is_file($optimizedLogo) ? $optimizedLogo : '';
        }

        if (! is_file($optimizedLogo) || filemtime($sourceLogo) > filemtime($optimizedLogo)) {
            self::regenerateUinPrintLogo();
        }

        return is_file($optimizedLogo) ? $optimizedLogo : $sourceLogo;
    }

    public static function toDataUri(?string $absolutePath, int $maxWidth = 600, int $maxHeight = 250): ?string
    {
        if (! $absolutePath || ! is_file($absolutePath)) {
            return null;
        }

        if (function_exists('imagecreatefromjpeg') && function_exists('imagejpeg')) {
            return self::optimizeWithGd($absolutePath, $maxWidth, $maxHeight);
        }

        return self::optimizeWithMagickDataUri($absolutePath, $maxWidth, $maxHeight);
    }

    private static function optimizeWithGd(string $absolutePath, int $maxWidth, int $maxHeight): ?string
    {
        $imageInfo = @getimagesize($absolutePath);
        if ($imageInfo === false) {
            return null;
        }

        [$width, $height, $type] = $imageInfo;
        $ratio = min($maxWidth / max($width, 1), $maxHeight / max($height, 1), 1);
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($absolutePath),
            IMAGETYPE_PNG => @imagecreatefrompng($absolutePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolutePath) : false,
            IMAGETYPE_GIF => @imagecreatefromgif($absolutePath),
            default => false,
        };

        if ($src === false) {
            return null;
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        if ($dst === false) {
            imagedestroy($src);

            return null;
        }

        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        imagejpeg($dst, null, 80);
        $jpegData = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        if ($jpegData === false || $jpegData === '') {
            return null;
        }

        return 'data:image/jpeg;base64,' . base64_encode($jpegData);
    }

    private static function optimizeWithMagickDataUri(string $absolutePath, int $maxWidth, int $maxHeight): ?string
    {
        $tempOut = tempnam(sys_get_temp_dir(), 'pdfimg_');
        if ($tempOut === false) {
            return null;
        }

        $tempJpg = $tempOut . '.jpg';
        @unlink($tempOut);

        if (! self::resizeWithMagick($absolutePath, $tempJpg, $maxWidth, $maxHeight)) {
            @unlink($tempJpg);

            return null;
        }

        $jpegData = file_get_contents($tempJpg);
        @unlink($tempJpg);

        if ($jpegData === false || $jpegData === '') {
            return null;
        }

        return 'data:image/jpeg;base64,' . base64_encode($jpegData);
    }

    private static function resizeWithMagick(string $source, string $destination, int $maxWidth, int $maxHeight): bool
    {
        $convert = '/usr/bin/convert';
        if (! is_executable($convert) || ! is_file($source)) {
            return false;
        }

        $command = sprintf(
            '%s %s -auto-orient -resize %dx%d> -strip -quality 80 %s 2>/dev/null',
            escapeshellarg($convert),
            escapeshellarg($source),
            $maxWidth,
            $maxHeight,
            escapeshellarg($destination),
        );

        exec($command, $output, $exitCode);

        return $exitCode === 0 && is_file($destination);
    }
}