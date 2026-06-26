@php
    $tz = 'Asia/Jakarta';
    $formatWib = static function ($dateTime, string $pattern = 'd F Y H:i') use ($tz): string {
        if (! $dateTime) {
            return '-';
        }

        return $dateTime->copy()->timezone($tz)->locale('id')->translatedFormat($pattern) . ' WIB';
    };
    $formatWibShort = static fn ($dateTime) => $formatWib($dateTime, 'd F Y H:i');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Ujian - {{ $room->title }}</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        body, div, p, table, thead, tbody, tr, td, th {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.45;
            color: #111;
        }
        .kop-surat {
            width: 100%;
            border-bottom: 3px solid #111;
            margin-bottom: 2px;
        }
        .kop-inner {
            border-bottom: 1px solid #111;
            display: table;
            width: 100%;
            padding-bottom: 10px;
        }
        .logo {
            display: table-cell;
            width: 100px;
            vertical-align: middle;
        }
        .logo img {
            width: 90px;
            height: auto;
        }
        .text-kop {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }
        .text-kop .line-main {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.35;
        }
        .text-kop .line-sub {
            font-size: 10pt;
            line-height: 1.35;
        }
        .doc-header {
            text-align: center;
            margin: 18px 0 16px;
        }
        .doc-header .title {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .doc-header .subtitle {
            margin-top: 6px;
            font-size: 11pt;
        }
        .doc-header .meta {
            margin-top: 4px;
            font-size: 10pt;
            color: #333;
        }
        .section-title {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 14px 0 8px;
            letter-spacing: 0.3px;
        }
        .info-table,
        .summary-table,
        .result-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .info-table .label {
            width: 145px;
            font-weight: bold;
        }
        .info-table .sep {
            width: 12px;
        }
        .summary-table {
            margin-top: 4px;
            border: 1px solid #111;
        }
        .summary-table th,
        .summary-table td {
            border: 1px solid #111;
            padding: 7px 10px;
            text-align: center;
            font-size: 10.5pt;
        }
        .summary-table th {
            font-weight: bold;
            text-transform: uppercase;
            background: #f2f2f2;
        }
        .summary-table td.value {
            font-size: 12pt;
            font-weight: bold;
        }
        .result-table {
            margin-top: 6px;
            border: 1px solid #111;
        }
        .result-table th,
        .result-table td {
            border: 1px solid #111;
            padding: 5px 4px;
            font-size: 8.5pt;
            vertical-align: middle;
        }
        .result-table th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .result-table td.center { text-align: center; }
        .result-table td.right { text-align: right; }
        .result-table tbody tr:nth-child(even) {
            background: #fafafa;
        }
        .no-data {
            margin-top: 20px;
            padding: 18px;
            border: 1px dashed #999;
            text-align: center;
            font-style: italic;
            color: #555;
        }
        .doc-footer {
            margin-top: 28px;
            padding-top: 10px;
            border-top: 1px solid #999;
            font-size: 9pt;
            color: #444;
        }
        .doc-footer p + p {
            margin-top: 3px;
        }
        .signature-block {
            margin-top: 36px;
            width: 240px;
            margin-left: auto;
            text-align: center;
            font-size: 10pt;
        }
        .signature-block .place-date {
            margin-bottom: 52px;
        }
        .signature-block .name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <div class="kop-inner">
            <div class="logo">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" width="90" height="90" alt="Logo UIN Saizu">
                @endif
            </div>
            <div class="text-kop">
                <div class="line-main">KEMENTRIAN AGAMA REPUBLIK INDONESIA</div>
                <div class="line-main">UNIVERSITAS ISLAM NEGERI</div>
                <div class="line-main">PROFESOR KIAI HAJI SAIFUDDIN ZUHRI</div>
                <div class="line-sub">Jalan MT. Haryono, Karangsentul, Kec. Padamara, Kab. Purbalingga, Jawa Tengah 53372</div>
                <div class="line-sub">Telepon (0281) 635624 Faksimili (0281) 636553</div>
                <div class="line-sub">www.uinsaizu.ac.id</div>
            </div>
        </div>
    </div>

    <div class="doc-header">
        <div class="title">Laporan Hasil Ujian</div>
        <div class="subtitle">{{ $room->title }}</div>
        <div class="meta">Kode Ruang: {{ $room->room_code }} &mdash; Dicetak: {{ $exportDate }}</div>
    </div>

    <div class="section-title">I. Informasi Ujian</div>
    <table class="info-table">
        <tr>
            <td class="label">Mata Kuliah</td>
            <td class="sep">:</td>
            <td>{{ $room->proposal?->course?->course_name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Waktu Pelaksanaan</td>
            <td class="sep">:</td>
            <td>{{ $formatWib($room->start_at) }} s.d. {{ $formatWib($room->end_at) }}</td>
        </tr>
        <tr>
            <td class="label">Durasi</td>
            <td class="sep">:</td>
            <td>{{ $room->duration_minutes }} menit</td>
        </tr>
        <tr>
            <td class="label">Jumlah Soal</td>
            <td class="sep">:</td>
            <td>{{ $totalQuestions }} soal</td>
        </tr>
    </table>

    <div class="section-title">II. Ringkasan Hasil</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Total Peserta</th>
                <th>Sudah Dikoreksi</th>
                <th>Rata-rata Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="value">{{ $totalAttempts }}</td>
                <td class="value">{{ $gradedAttempts }}</td>
                <td class="value">{{ number_format($avgScore, 1, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">III. Daftar Peserta</div>
    @if($attempts->count() > 0)
        <table class="result-table">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 12%;">NIM</th>
                    <th style="width: 22%;">Nama Mahasiswa</th>
                    <th style="width: 18%;">Waktu Submit</th>
                    <th style="width: 14%;">Status</th>
                    <th style="width: 8%;">Pelanggaran</th>
                    <th style="width: 8%;">Dijawab</th>
                    <th style="width: 8%;">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attempts as $index => $attempt)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $attempt->user->identity_id ?? '-' }}</td>
                        <td>{{ $attempt->user->name ?? '-' }}</td>
                        <td class="center">{{ $formatWibShort($attempt->submitted_at) }}</td>
                        <td class="center">{{ $attempt->statusLabel() }}</td>
                        <td class="center">{{ $attempt->tab_switch_count }} kali</td>
                        <td class="center">{{ $attempt->answered_count }}/{{ $totalQuestions }}</td>
                        <td class="right">
                            @if($attempt->score !== null)
                                {{ number_format($attempt->score, 1, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Belum ada peserta yang menyelesaikan ujian.</div>
    @endif

    <div class="signature-block">
        <div class="place-date">
            Purbalingga, {{ now()->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y') }}
        </div>
        <div>Penanggung Jawab,</div>
        <div class="name">{{ $exportBy }}</div>
    </div>

    <div class="doc-footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh sistem ujian Sainteku.</p>
        <p>Dicetak oleh: {{ $exportBy }} pada {{ $exportDate }}</p>
    </div>
</body>
</html>