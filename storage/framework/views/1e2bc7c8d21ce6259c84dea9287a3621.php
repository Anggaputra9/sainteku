<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Soal Ujian - <?php echo e($proposal->course->course_name); ?></title>
    <style>
        /* Kasih padding bottom yang agak lega biar soal terakhir ga ketabrak TTD */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 10px 10px 200px 10px;
            position: relative;
        }

        .kop-surat {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
            overflow: hidden;
        }

        .logo-box {
            float: left;
            width: 70px;
        }

        .logo-img {
            width: 100%;
            height: auto;
            display: block;
        }

        .text-box {
            float: left;
            width: calc(100% - 85px);
            padding-left: 15px;
            text-align: center;
        }

        .text-box h2 {
            margin: 0;
            font-size: 14pt;
        }

        .text-box h3 {
            margin: 0;
            font-size: 12pt;
            text-transform: uppercase;
        }

        .text-box p {
            margin: 2px 0;
            font-size: 10pt;
        }

        .clear {
            clear: both;
        }

        .document-title {
            text-align: center;
            margin-top: 10px;
            padding-top: 5px;
        }

        .document-title h3 {
            margin: 0;
            font-size: 12pt;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .info-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 2px 5px;
            font-weight: bold;
            font-size: 10pt;
            vertical-align: top;
        }

        /* CSS Tambahan Buat Petunjuk Ujian */
        .instruction-section {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .instruction-section p.title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 11pt;
        }

        .instruction-section ol {
            margin-top: 0;
            padding-left: 20px;
            text-align: justify;
            font-size: 11pt;
        }

        .instruction-section ol li {
            margin-bottom: 3px;
        }

        .question-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 11pt;
        }

        .question-list {
            width: 100%;
            border-collapse: collapse;
        }

        .question-item td {
            padding: 5px 0;
            vertical-align: top;
            text-align: justify;
        }

        /* TTD FIXED DI BAWAH */
        .signature-section {
            position: absolute;
            bottom: 30px;
            right: 30px;
            width: 250px;
            text-align: left;
        }

        .signature-img {
            height: 70px;
            width: auto;
            margin: 5px 0;
            display: block;
        }
    </style>
</head>

<body>

    
    <div class="kop-surat">
        <div class="logo-box">
            <?php if($logoBase64): ?>
                <img src="<?php echo e($logoBase64); ?>" class="logo-img" alt="Logo UIN">
            <?php endif; ?>
        </div>
        <div class="text-box">
            <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
            <h3>UNIVERSITAS ISLAM NEGERI (UIN) PROF. K.H. SAIFUDDIN ZUHRI PURWOKERTO</h3>
            <p>Alamat: Jl. Jend. A. Yani No. 40 A Purwokerto 53126 Telp: 0281 635624, 628250, Fax: 0281 636553</p>
            <p>Website: www.uinsaizu.ac.id</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="document-title">
        <h3>LEMBAR SOAL <?php echo e($proposal->exam_type); ?></h3>
    </div>

    
    <table class="info-table">
        <tr>
            <td width="20%">MATA KULIAH</td>
            <td width="2%">:</td>
            <td width="40%"><?php echo e($proposal->course->course_name); ?></td>
            <td width="15%">SEMESTER</td>
            <td width="2%">:</td>
            <td width="21%">Gasal 2024/2025</td>
        </tr>
        <tr>
            <td>DOSEN PENGAMPU</td>
            <td>:</td>
            <td><?php echo e(ucwords(strtolower($proposal->creator->name ?? '-'))); ?></td>
            <td>JENIS UJIAN</td>
            <td>:</td>
            <td><?php echo e(strtoupper($proposal->exam_type)); ?></td>
        </tr>
    </table>

    <div style="border-top: 2px double #000; margin-top: 5px; margin-bottom: 10px;"></div>

    
    <div class="instruction-section">
        <p class="title">PETUNJUK UJIAN!</p>
        <ol type="a">
            <li>Berdoalah terlebih dahulu sebelum mulai mengerjakan soal ujian.</li>
            <li>Tulislah Nama, NIM, dan Kelas pada lembar jawaban yang telah disediakan.</li>
            <li>Bacalah setiap soal dengan saksama dan kerjakan dengan teliti.</li>
            <li>Bekerjalah dengan jujur dan percayalah pada kemampuan diri Anda masing-masing.</li>
            <li>Periksa kembali seluruh jawaban Anda sebelum diserahkan/dikumpulkan kepada pengawas ujian.</li>
        </ol>
    </div>

    
    <p class="question-title">SOAL</p>
    <table class="question-list">
        <?php $__currentLoopData = $proposal->examQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="question-item">
                <td width="30px" style="font-weight: bold;"><?php echo e($eq->order_no); ?>.</td>
                <td>
                    <span><?php echo nl2br(e($eq->question->question_text)); ?></span>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>

    
    <div class="signature-section">
        <p style="margin-bottom: 0;">Purwokerto,
            <?php echo e(\Carbon\Carbon::parse($proposal->updated_at)->translatedFormat('d F Y')); ?></p>
        <p style="margin-top: 0;">Ketua Program Studi,</p>

        <?php if($kaprodi && $kaprodi->signature): ?>
            <img src="<?php echo e($kaprodi->signature); ?>" class="signature-img">
        <?php else: ?>
            <div style="height: 70px;"></div>
        <?php endif; ?>

        <p style="margin-bottom: 0; font-weight: bold; text-decoration: underline;">
            <?php echo e($kaprodi ? ucwords(strtolower($kaprodi->name)) : '................................................'); ?>

        </p>
        <p style="margin-top: 0;">
            NIP. <?php echo e($kaprodi ? $kaprodi->identity_id : '................................................'); ?>

        </p>
    </div>

</body>

</html><?php /**PATH C:\laragon\www\sainteku\Modules/MonevAkademik\resources/views/tashih/print.blade.php ENDPATH**/ ?>