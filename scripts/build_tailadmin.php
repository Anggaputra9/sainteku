<?php
// Simple helper to build Tailadmin template and copy build output to public/tailadmin
set_time_limit(0);

$root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
$templateDir = $root . 'public' . DIRECTORY_SEPARATOR . 'tailadmin-free-tailwind-dashboard-template-main';
$buildDir = $templateDir . DIRECTORY_SEPARATOR . 'build';
$targetDir = $root . 'public' . DIRECTORY_SEPARATOR . 'tailadmin';

echo "Building Tailadmin from: $templateDir\n";

// Run npm install and npm run build
chdir($templateDir);
echo "Running npm install...\n";
passthru('npm install', $code1);
if ($code1 !== 0) {
    echo "npm install failed with code $code1\n";
    exit(1);
}

echo "Running npm run build...\n";
passthru('npm run build', $code2);
if ($code2 !== 0) {
    echo "npm run build failed with code $code2\n";
    exit(1);
}

// Copy build dir to public/tailadmin
echo "Copying build to $targetDir ...\n";

function rrmdir_copy($src, $dst) {
    if (!is_dir($src)) return false;
    if (!is_dir($dst)) mkdir($dst, 0755, true);
    $dir = opendir($src);
    while(false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            $srcPath = $src . DIRECTORY_SEPARATOR . $file;
            $dstPath = $dst . DIRECTORY_SEPARATOR . $file;
            if (is_dir($srcPath)) {
                rrmdir_copy($srcPath, $dstPath);
            } else {
                copy($srcPath, $dstPath);
            }
        }
    }
    closedir($dir);
    return true;
}

if (!is_dir($buildDir)) {
    echo "Build directory not found: $buildDir\n";
    exit(1);
}

rrmdir_copy($buildDir, $targetDir);

echo "Done. Built assets copied to public/tailadmin\n";

exit(0);
