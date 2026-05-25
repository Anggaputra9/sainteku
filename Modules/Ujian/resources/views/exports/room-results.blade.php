<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - {{ $room->title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 16pt;
            margin-bottom: 5px;
            color: #1a1a1a;
        }
        .header h2 {
            font-size: 14pt;
            margin-bottom: 3px;
            color: #444;
        }
        .header p {
            font-size: 9pt;
            color: #666;
        }
        .info-section {
            margin-bottom: 15px;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .info-value {
            flex: 1;
        }
        .stats-box {
            display: flex;
            justify-content: space-around;
            margin: 15px 0;
            padding: 10px;
            background: #e8f4f8;
            border-radius: 5px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-label {
            font-size: 8pt;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .stat-value {
            font-size: 14pt;
            font-weight: bold;
            color: #1a73e8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        thead {
            background: #1a73e8;
            color: white;
        }
        th {
            padding: 8px 5px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
            font-size: 9pt;
        }
        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        tbody tr:hover {
            background: #f0f0f0;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-secondary {
            background: #e2e3e5;
            color: #383d41;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8pt;
            color: #666;
            text-align: center;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN HASIL UJIAN</h1>
        <h2>{{ $room->title }}</h2>
        <p>Kode Ruang: {{ $room->room_code }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Mata Kuliah:</div>
            <div class="info-value">{{ $room->proposal->course->name ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Waktu Ujian:</div>
            <div class="info-value">{{ $room->start_at->format('d M Y H:i') }} - {{ $room->end_at->format('d M Y H:i') }} WIB</div>
        </div>
        <div class="info-row">
            <div class="info-label">Durasi:</div>
            <div class="info-value">{{ $room->duration_minutes }} menit</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jumlah Soal:</div>
            <div class="info-value">{{ $room->proposal->examQuestions->count() }} soal</div>
        </div>
    </div>

    <div class="stats-box">
        <div class="stat-item">
            <div class="stat-label">Total Peserta</div>
            <div class="stat-value">{{ $totalAttempts }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Sudah Dikoreksi</div>
            <div class="stat-value">{{ $gradedAttempts }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-label">Rata-rata Nilai</div>
            <div class="stat-value">{{ $avgScore }}</div>
        </div>
    </div>

    @if($attempts->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">NIM</th>
                    <th style="width: 25%;">Nama</th>
                    <th style="width: 15%;" class="text-center">Waktu Submit</th>
                    <th style="width: 12%;" class="text-center">Status</th>
                    <th style="width: 10%;" class="text-center">Pelanggaran</th>
                    <th style="width: 10%;" class="text-center">Dijawab</th>
                    <th style="width: 8%;" class="text-right">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attempts as $index => $attempt)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $attempt->user->identity_id }}</td>
                        <td>{{ $attempt->user->name }}</td>
                        <td class="text-center">{{ $attempt->submitted_at?->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @php
                                $badgeClass = match($attempt->status) {
                                    'SUBMITTED' => 'badge-success',
                                    'AUTO_SUBMITTED_TIME' => 'badge-warning',
                                    'AUTO_SUBMITTED_VIOLATION' => 'badge-danger',
                                    default => 'badge-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ str_replace('_', ' ', $attempt->status) }}
                            </span>
                        </td>
                        <td class="text-center">{{ $attempt->tab_switch_count }}x</td>
                        <td class="text-center">{{ $attempt->answers->where('is_answered', true)->count() }}/{{ $room->proposal->examQuestions->count() }}</td>
                        <td class="text-right">
                            @if($attempt->score !== null)
                                <strong>{{ number_format($attempt->score, 2) }}</strong>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            Belum ada peserta yang menyelesaikan ujian.
        </div>
    @endif

    <div class="footer">
        <p>Dicetak oleh: {{ $exportBy }} | Tanggal: {{ $exportDate }}</p>
        <p>Dokumen ini dibuat secara otomatis oleh sistem.</p>
    </div>
</body>
</html>
