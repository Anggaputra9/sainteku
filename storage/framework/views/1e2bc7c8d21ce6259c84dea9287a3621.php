<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Soal - <?php echo e($proposal->course->course_name ?? 'Mata Kuliah'); ?></title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.3;
            color: black;
        }

        .kop-surat {
            width: 100%;
            border-bottom: 3px solid black;
            display: table;
            padding-bottom: 2px;
        }

        .kop-inner {
            border-bottom: 1px solid black;
            display: table;
            width: 100%;
            padding-bottom: 10px;
        }

        .logo {
            display: table-cell;
            width: 100px;
            vertical-align: middle;
        }

        .text-kop {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        .text-kop h2,
        .text-kop h3,
        .text-kop h4,
        .text-kop p {
            margin: 0;
        }

        .judul {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .meta-table td {
            padding: 4px;
            vertical-align: top;
        }

        .label {
            width: 25%;
            font-weight: bold;
        }

        .box {
            border: 1px solid black;
            padding: 10px;
            margin-bottom: 20px;
        }

        .box h4 {
            margin: 0 0 5px 0;
        }

        .soal-item {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .img-soal {
            max-width: 100%;
            max-height: 250px;
            display: block;
            margin: 10px 0;
        }

        .ttd-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .ttd-table td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }

        .ttd-img {
            max-height: 80px;
            display: block;
            margin: 5px auto;
        }

        .underline {
            text-decoration: underline;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="kop-surat">
        <div class="kop-inner">
            <div class="logo"><img src="<?php echo e($logoBase64); ?>" width="90" alt="Logo UIN"></div>
            <div class="text-kop">
                <h4>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h4>
                <h2>UNIVERSITAS ISLAM NEGERI</h2>
                <h2>PROFESOR KIAI HAJI SAIFUDDIN ZUHRI</h2>
                <h3>FAKULTAS SAINS DAN TEKNOLOGI</h3>
                <p>Jl. MT. Haryono, Karangsentul, Kec. Padamara, Kabupaten Purbalingga.</p>
                <p>Telepon (0281) 635624 Faksimili (0281) 636553</p>
                <p>www.uinsaizu.ac.id</p>
            </div>
        </div>
    </div>

    <div class="judul">
        NASKAH SOAL <?php echo e($proposal->exam_type == 'UTS' ? 'UJIAN TENGAH SEMESTER' : 'UJIAN AKHIR SEMESTER'); ?><br>
        TAHUN AKADEMIK <?php echo e(optional($proposal->period)->name ?? ''); ?>

    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Program Studi</td>
            
            <td>: <?php echo e($unitName); ?></td>
        </tr>
        <tr>
            <td class="label">Mata Kuliah</td>
            <td>: <?php echo e(optional($proposal->course)->course_name ?? '-'); ?></td>
        </tr>
        <tr>
            <td class="label">Dosen Penguji</td>
            <td>: <?php echo e(optional($proposal->creator)->name ?? '-'); ?></td>
        </tr>
    </table>

    <div class="box">
        <h4>PETUNJUK UJIAN:</h4>
        <ul style="margin: 0; padding-left: 20px;">
            <li>Berdoalah sebelum memulai dan sesudah menyelesaikan ujian.</li>
            <li>Kerjakan soal dengan jujur dan dilarang bekerja sama dalam bentuk apapun.</li>
            <li>Jawablah pertanyaan dengan jelas, sistematis, dan mudah terbaca.</li>
            <li>Dilarang menggunakan alat komunikasi atau akses internet selama ujian berlangsung, kecuali ada instruksi khusus dari pengawas.</li>
        </ul>
    </div>

    <div style="margin-left: 20px;">
        <?php $__currentLoopData = $proposal->examQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $eq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="soal-item">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 25px; vertical-align: top;"><?php echo e($index + 1); ?>.</td>
                        <td>
                            <?php echo nl2br(e(optional($eq->question)->question_text)); ?>


                            
                            <?php if(optional($eq->question)->image_path): ?>
                                <?php
                                    $cleanImgPath = ltrim($eq->question->image_path, '/');
                                    // Tembak langsung ke direktori fisik storage bawaan Laravel
                                    $physicalPath = storage_path('app/public/' . $cleanImgPath);
                                    $imgBase64 = '';

                                    // Kalau filenya beneran ada di harddisk server, convert ke Base64
                                    if (file_exists($physicalPath)) {
                                        $ext = pathinfo($physicalPath, PATHINFO_EXTENSION);
                                        $data = file_get_contents($physicalPath);
                                        $imgBase64 = 'data:image/' . $ext . ';base64,' . base64_encode($data);
                                    }
                                ?>

                                <?php if($imgBase64 != ''): ?>
                                    <br>
                                    <img src="<?php echo e($imgBase64); ?>" class="img-soal">
                                <?php else: ?>
                                    <br>
                                    <small style="color:red; font-style:italic;">[Gambar tidak ditemukan di storage:
                                        <?php echo e($cleanImgPath); ?>]</small>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <table class="ttd-table">
        <tr>
            <td>
                Dosen Pengampu,<br><br>
                <?php if(optional($proposal->creator)->signature): ?>
                    <img src="<?php echo e($proposal->creator->signature); ?>" class="ttd-img" alt="TTD Dosen">
                <?php else: ?>
                    <br><br><br>
                <?php endif; ?>
                <div class="underline"><?php echo e(optional($proposal->creator)->name ?? '-'); ?></div>
                NIP. <?php echo e(optional($proposal->creator)->identity_id ?? '-'); ?>

            </td>
            <td>
                Purwokerto, <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?><br>
                Mengetahui, Ketua Program Studi<br><br>
                <?php if(isset($kaprodi) && $kaprodi->signature): ?>
                    <img src="<?php echo e($kaprodi->signature); ?>" class="ttd-img" alt="TTD Kaprodi">
                <?php else: ?>
                    <br><br><br>
                <?php endif; ?>
                <div class="underline"><?php echo e(optional($kaprodi)->name ?? '..........................'); ?></div>
                NIP. <?php echo e(optional($kaprodi)->identity_id ?? '-'); ?>

            </td>
        </tr>
    </table>

</body>

</html><?php /**PATH C:\laragon\www\sainteku\Modules/MonevAkademik\resources/views/tashih/print.blade.php ENDPATH**/ ?>