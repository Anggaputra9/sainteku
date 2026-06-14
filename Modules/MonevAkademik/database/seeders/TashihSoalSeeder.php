<?php

namespace Modules\MonevAkademik\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seeder untuk Tashih Soal.
 *
 * - Dosen pengaju (created_by) : U0002 (Arifian Ilham Nurriandana) - data eksisting
 * - Kaprodi (approved_by)      : U0003 (Anas Azimi Qalban) - data eksisting
 * - Mata Kuliah                : INF002 (Pemrograman Web Lanjut)
 * - Periode                    : id = 1 (2024/2025 Gasal, aktif)
 * - 1 Proposal Ujian (UAS)     : status APPROVED
 *   berisi 10 butir soal (bobot @10, total = 100) dengan CPMK bervariasi.
 */
class TashihSoalSeeder extends Seeder
{
    public function run(): void
    {
        // ============== KONFIGURASI DATA UTAMA ==============
        $dosenId    = 'U0002'; // Pengaju
        $kaprodiId  = 'U0003'; // Approver / Reviewer
        $courseId   = 'INF002'; // Pemrograman Web Lanjut
        $periodId   = 1;        // 2024/2025 Gasal
        $examType   = 'UAS';

        // ============== VALIDASI DEPENDENSI ==============
        $checks = [
            'mst_user (Dosen U0002)'   => DB::table('mst_user')->where('id', $dosenId)->exists(),
            'mst_user (Kaprodi U0003)' => DB::table('mst_user')->where('id', $kaprodiId)->exists(),
            'mst_course (INF002)'      => DB::table('mst_course')->where('id', $courseId)->exists(),
            'mst_period (id=1)'        => DB::table('mst_period')->where('id', $periodId)->exists(),
            'mst_cpmk (CP-01..CP-04)'  => DB::table('mst_cpmk')->whereIn('id', ['CP-01', 'CP-02', 'CP-03', 'CP-04'])->count() === 4,
        ];

        foreach ($checks as $label => $ok) {
            if (! $ok) {
                $this->command->warn("⚠️  Lewatin TashihSoalSeeder: dependensi `{$label}` belum tersedia. Jalankan DatabaseSeeder utama dulu.");
                return;
            }
        }

        // ============== HINDARI DOUBLE INSERT ==============
        $existingProposal = DB::table('trx_exam_proposals')
            ->where('course_id', $courseId)
            ->where('exam_type', $examType)
            ->where('period_id', $periodId)
            ->where('created_by', $dosenId)
            ->first();

        if ($existingProposal) {
            $this->command->info("ℹ️  Proposal Tashih ({$examType} {$courseId} periode {$periodId}) sudah ada, seeder dilewati.");
            return;
        }

        // ============== DAFTAR 10 BUTIR SOAL ==============
        // Setiap item: question_text, cpmk_id (array), weight (total = 100)
        $questions = [
            [
                'cpmk_id'       => ['CP-01'],
                'question_text' => 'Jelaskan perbedaan mendasar antara arsitektur Monolithic, Microservices, dan Serverless pada pengembangan aplikasi web modern. Sertakan kelebihan dan kekurangan masing-masing.',
                'weight'        => 10,
            ],
            [
                'cpmk_id'       => ['CP-01', 'CP-02'],
                'question_text' => 'Uraikan siklus request–response pada aplikasi web berbasis Laravel mulai dari masuknya request ke entry point hingga response dikirim balik ke browser. Sebutkan komponen kunci yang terlibat (Kernel, Middleware, Router, Controller, View).',
                'weight'        => 10,
            ],
            [
                'cpmk_id'       => ['CP-02'],
                'question_text' => 'Buatlah contoh implementasi RESTful API dengan endpoint CRUD untuk resource "Article" menggunakan Laravel. Tuliskan method, URI, dan deskripsi singkat tiap endpoint pada tabel.',
                'weight'        => 10,
            ],
            [
                'cpmk_id'       => ['CP-02', 'CP-03'],
                'question_text' => 'Diberikan kode controller berikut yang melakukan query N+1, tuliskan analisis masalah dan refactor kodenya agar lebih efisien menggunakan Eager Loading.',
                'weight'        => 10,
            ],
            [
                'cpmk_id'       => ['CP-03'],
                'question_text' => 'Sebuah aplikasi web mengalami penurunan performa drastis ketika trafik meningkat di atas 5.000 request/menit. Lakukan analisis kemungkinan bottleneck dan usulkan tiga strategi optimasi yang relevan.',
                'weight'        => 10,
            ],
            [
                'cpmk_id'       => ['CP-03', 'CP-04'],
                'question_text' => 'Rancang skema autentikasi dan otorisasi pada sebuah aplikasi web multi-role (Admin, Dosen, Mahasiswa) menggunakan kombinasi Laravel Gate dan Policy. Gambarkan flow-nya dalam bentuk diagram singkat.',
                'weight'        => 10,
            ],
            [
                'cpmk_id'       => ['CP-04'],
                'question_text' => 'Rancang struktur database (ERD) untuk modul "Pengajuan Tashih Soal" yang minimal mencakup entitas: Dosen, Mata Kuliah, Pengajuan, Soal, dan Review Kaprodi. Sertakan relasi dan kardinalitasnya.',
                'weight'        => 10,
            ],
            [
                'cpmk_id'       => ['CP-02', 'CP-04'],
                'question_text' => 'Implementasikan validasi form pengajuan ujian di Laravel menggunakan Form Request, di mana total bobot soal harus sama dengan 100 dan minimal terdapat 5 butir soal. Tuliskan kelas Form Request lengkap dengan rules dan messages.',
                'weight'        => 10,
            ],
            [
                'cpmk_id'       => ['CP-01', 'CP-03'],
                'question_text' => 'Jelaskan konsep Same-Origin Policy (SOP) dan CORS pada aplikasi web. Berikan contoh konfigurasi CORS pada Laravel agar API hanya dapat diakses oleh domain frontend tertentu.',
                'weight'        => 10,
            ],
            [
                'cpmk_id'       => ['CP-03', 'CP-04'],
                'question_text' => 'Studi kasus: sebuah aplikasi e-Learning membutuhkan fitur upload tugas berukuran besar (>50MB) dengan progress bar. Rancang solusi end-to-end (frontend, backend, storage) yang aman dan scalable, sertakan justifikasi teknologinya.',
                'weight'        => 10,
            ],
        ];

        // Sanity check bobot total = 100
        $totalWeight = array_sum(array_column($questions, 'weight'));
        if ($totalWeight !== 100) {
            $this->command->error("❌ Total bobot soal = {$totalWeight}, harus 100. Seeder dibatalkan.");
            return;
        }

        // ============== EKSEKUSI INSERT (TRANSAKSI) ==============
        DB::transaction(function () use ($questions, $dosenId, $kaprodiId, $courseId, $periodId, $examType) {
            $now = now();

            // 1) Buat Proposal (langsung APPROVED)
            $proposalId = DB::table('trx_exam_proposals')->insertGetId([
                'uuid'        => (string) Str::uuid(),
                'period_id'   => $periodId,
                'course_id'   => $courseId,
                'exam_type'   => $examType,
                'status'      => 'APPROVED',
                'created_by'  => $dosenId,
                'approved_by' => $kaprodiId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            // 2) Buat 10 Question + pivot ExamQuestion
            foreach ($questions as $idx => $q) {
                $orderNo = $idx + 1;

                $questionId = DB::table('trx_questions')->insertGetId([
                    'uuid'          => (string) Str::uuid(),
                    'course_id'     => $courseId,
                    'cpmk_id'       => json_encode($q['cpmk_id']),
                    'question_text' => $q['question_text'],
                    'image_path'    => null,
                    'created_by'    => $dosenId,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);

                DB::table('trx_exam_questions')->insert([
                    'proposal_id' => $proposalId,
                    'question_id' => $questionId,
                    'order_no'    => $orderNo,
                    'weight'      => $q['weight'],
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }

            // 3) Catat 1 review/komentar persetujuan dari Kaprodi
            DB::table('trx_exam_reviews')->insert([
                'proposal_id' => $proposalId,
                'reviewer_id' => $kaprodiId,
                'comment'     => 'Soal sudah sesuai dengan CPMK dan distribusi bobot proporsional. Disetujui untuk digunakan pada ujian.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            // 4) Tambahkan log "Approved" agar history terisi
            DB::table('trx_exam_question_logs')->insert([
                'proposal_id' => $proposalId,
                'order_no'    => 0,
                'user_id'     => $kaprodiId,
                'type'        => 'Persetujuan Kaprodi',
                'message'     => 'Pengajuan disetujui (APPROVED) seluruhnya tanpa revisi.',
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        });

        $this->command->info('✅ TashihSoalSeeder selesai: 1 Proposal APPROVED + 10 Soal berhasil dibuat.');
        $this->command->info("   Pengaju: {$dosenId} | Approver: {$kaprodiId} | Matkul: {$courseId} | Tipe: {$examType}");
    }
}
