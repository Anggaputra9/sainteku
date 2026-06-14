<?php

namespace Modules\MonevAkademik\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Contoh pengajuan Tashih Soal:
 * - Pengaju : Arifian Ilham Nurriandana (DSN-INF-0000001)
 * - Asesor  : Anas Azimi Qalban (KPD-INF-0000001) — status APPROVED
 * - Matkul  : INF002 Pengolahan Citra Digital
 * - 15 butir soal UAS, total bobot 100 (10×7 + 5×6)
 */
class ArifianContohSoalSeeder extends Seeder
{
    public function run(): void
    {
        $dosenId = 'DSN-INF-0000001';
        $kaprodiId = 'KPD-INF-0000001';
        $courseId = 'INF002';
        $periodId = 1;
        $examType = 'UAS';

        $this->seedPeriodIfMissing($periodId);
        $this->seedCpmkIfMissing();

        if (! DB::table('mst_user')->where('id', $dosenId)->exists()) {
            $this->log("User dosen {$dosenId} tidak ditemukan.", 'error');

            return;
        }

        if (! DB::table('mst_course')->where('id', $courseId)->exists()) {
            $this->log("Mata kuliah {$courseId} tidak ditemukan.", 'error');

            return;
        }

        $existing = DB::table('trx_exam_proposals')
            ->where('course_id', $courseId)
            ->where('exam_type', $examType)
            ->where('period_id', $periodId)
            ->where('created_by', $dosenId)
            ->first();

        if ($existing) {
            $count = DB::table('trx_exam_questions')->where('proposal_id', $existing->id)->count();
            $this->log("Pengajuan sudah ada (id={$existing->id}, {$count} soal). Seeder dilewati.");

            return;
        }

        $questions = [
            ['cpmk_id' => ['CP-01'], 'question_text' => 'Jelaskan perbedaan citra analog dan citra digital. Sertakan representasi matematis citra digital sebagai matriks serta arti nilai piksel dalam format grayscale.', 'weight' => 7],
            ['cpmk_id' => ['CP-01', 'CP-02'], 'question_text' => 'Sebuah citra berukuran 640×480 piksel disimpan dalam format RGB 24-bit tanpa kompresi. Hitung ukuran file dalam byte dan jelaskan pengaruh bit depth terhadap kualitas visual.', 'weight' => 7],
            ['cpmk_id' => ['CP-02'], 'question_text' => 'Uraikan langkah-langkah konversi citra RGB ke citra grayscale menggunakan bobot luminansi standar (0.299R + 0.587G + 0.114B). Berikan contoh perhitungan pada satu piksel.', 'weight' => 7],
            ['cpmk_id' => ['CP-02', 'CP-03'], 'question_text' => 'Jelaskan konsep histogram citra dan bagaimana histogram digunakan untuk menganalisis kecerahan serta kontras. Gambarkan contoh histogram citra gelap, normal, dan terang.', 'weight' => 7],
            ['cpmk_id' => ['CP-03'], 'question_text' => 'Deskripsikan prinsip operasi histogram equalization. Kapan teknik ini efektif dan kapan justru menurunkan kualitas citra? Berikan contoh kasus penggunaannya.', 'weight' => 7],
            ['cpmk_id' => ['CP-03', 'CP-04'], 'question_text' => 'Bandingkan metode peningkatan kontras manual (stretching linear) dengan histogram equalization dari sisi kelebihan, kekurangan, dan kompleksitas implementasi.', 'weight' => 7],
            ['cpmk_id' => ['CP-04'], 'question_text' => 'Jelaskan konvolusi 2D pada citra dan peran kernel/filter. Tuliskan kernel untuk operasi blur (rata-rata 3×3) dan edge detection Sobel arah X.', 'weight' => 7],
            ['cpmk_id' => ['CP-02', 'CP-04'], 'question_text' => 'Diberikan citra yang terganggu noise salt-and-pepper, bandingkan efektivitas filter median 3×3 dan filter mean 3×3. Jelaskan mengapa median lebih unggul pada kasus ini.', 'weight' => 7],
            ['cpmk_id' => ['CP-01', 'CP-03'], 'question_text' => 'Apa yang dimaksud dengan sampling dan quantization dalam proses digitalisasi citra? Jelaskan trade-off antara resolosi spasial, resolosi intensitas, dan ukuran data.', 'weight' => 7],
            ['cpmk_id' => ['CP-03', 'CP-04'], 'question_text' => 'Rancang alur kerja (pipeline) preprocessing citra medis MRI sebelum klasifikasi tumor: dari input DICOM hingga citra siap fitur. Sebutkan minimal empat tahap beserta tujuannya.', 'weight' => 7],
            ['cpmk_id' => ['CP-02'], 'question_text' => 'Jelaskan perbedaan ruang warna RGB, HSV, dan LAB. Pada skenario segmentasi objek berwarna di luar ruangan, ruang warna mana yang paling membantu dan mengapa?', 'weight' => 6],
            ['cpmk_id' => ['CP-03'], 'question_text' => 'Deskripsikan teknik thresholding global vs adaptif (Otsu vs adaptive mean). Berikan kondisi citra yang cocok untuk masing-masing metode.', 'weight' => 6],
            ['cpmk_id' => ['CP-04'], 'question_text' => 'Jelaskan morfologi citra biner: operasi erosi, dilasi, opening, dan closing. Berikan satu contoh aplikasi pada hasil segmentasi karakter tulisan tangan.', 'weight' => 6],
            ['cpmk_id' => ['CP-01', 'CP-04'], 'question_text' => 'Sebutkan dan jelaskan tiga metrik objektif untuk menilai kualitas citra hasil restorasi (mis. MSE, PSNR, SSIM). Apa kelemahan masing-masing metrik?', 'weight' => 6],
            ['cpmk_id' => ['CP-02', 'CP-03'], 'question_text' => 'Studi kasus: sistem OCR plat nomor kendaraan memiliki akurasi rendah saat malam hari. Analisis kemungkinan penyebab dari sisi pengolahan citra dan usulkan tiga solusi preprocessing yang relevan.', 'weight' => 6],
        ];

        $totalWeight = array_sum(array_column($questions, 'weight'));
        if ($totalWeight !== 100 || count($questions) !== 15) {
            $this->log("Sanity check gagal: {$totalWeight} bobot, " . count($questions) . ' soal.', 'error');

            return;
        }

        DB::transaction(function () use ($questions, $dosenId, $kaprodiId, $courseId, $periodId, $examType): void {
            $now = now();

            $proposalId = DB::table('trx_exam_proposals')->insertGetId([
                'uuid' => (string) Str::uuid(),
                'period_id' => $periodId,
                'course_id' => $courseId,
                'exam_type' => $examType,
                'status' => 'APPROVED',
                'created_by' => $dosenId,
                'approved_by' => $kaprodiId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($questions as $idx => $q) {
                $questionId = DB::table('trx_questions')->insertGetId([
                    'uuid' => (string) Str::uuid(),
                    'course_id' => $courseId,
                    'cpmk_id' => json_encode($q['cpmk_id']),
                    'question_text' => $q['question_text'],
                    'image_path' => null,
                    'created_by' => $dosenId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('trx_exam_questions')->insert([
                    'proposal_id' => $proposalId,
                    'question_id' => $questionId,
                    'order_no' => $idx + 1,
                    'weight' => $q['weight'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('trx_exam_reviews')->insert([
                'proposal_id' => $proposalId,
                'reviewer_id' => $kaprodiId,
                'comment' => 'Soal sudah sesuai CPMK dan distribusi bobot proporsional. Disetujui untuk digunakan pada ujian.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('trx_exam_question_logs')->insert([
                'proposal_id' => $proposalId,
                'order_no' => 0,
                'user_id' => $kaprodiId,
                'type' => 'Persetujuan Kaprodi',
                'message' => 'Pengajuan disetujui (APPROVED) seluruhnya tanpa revisi.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });

        $this->log('✅ 15 contoh soal berhasil dibuat.');
        $this->log("   Pengaju: Arifian ({$dosenId}) | Matkul: {$courseId} | Status: APPROVED");
        $this->log("   Asesor: Anas ({$kaprodiId})");
    }

    private function log(string $message, string $level = 'info'): void
    {
        if ($this->command) {
            $level === 'error' ? $this->command->error($message) : $this->command->info($message);

            return;
        }

        echo $message . PHP_EOL;
    }

    private function seedPeriodIfMissing(int $periodId): void
    {
        if (DB::table('mst_period')->where('id', $periodId)->exists()) {
            return;
        }

        DB::table('mst_period')->insert([
            'id' => $periodId,
            'name' => '2025/2026',
            'semester' => 'Gasal',
            'is_active' => '1',
            'created_at' => now(),
        ]);
    }

    private function seedCpmkIfMissing(): void
    {
        $cpmks = [
            ['id' => 'CP-01', 'name' => 'Memahami konsep dasar dan representasi citra digital'],
            ['id' => 'CP-02', 'name' => 'Menerapkan teknik transformasi dan ruang warna pada citra'],
            ['id' => 'CP-03', 'name' => 'Menganalisis dan meningkatkan kualitas citra'],
            ['id' => 'CP-04', 'name' => 'Merancang pipeline pengolahan citra untuk kasus nyata'],
        ];

        foreach ($cpmks as $cpmk) {
            if (DB::table('mst_cpmk')->where('id', $cpmk['id'])->exists()) {
                continue;
            }

            DB::table('mst_cpmk')->insert([
                'id' => $cpmk['id'],
                'name' => $cpmk['name'],
                'is_active' => '1',
                'created_at' => now(),
            ]);
        }
    }
}