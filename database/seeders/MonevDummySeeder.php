<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MonevDummySeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // 1. AMBIL ID ROLE DARI DATABASE
        // Sesuaikan dengan ID asli di tabel mst_role lu ya. Asumsi Dosen = 2, Kaprodi = 3
        $roleDosenId = DB::table('mst_role')->where('role_name', 'Dosen')->value('id') ?? 2;
        $roleKaprodiId = DB::table('mst_role')->where('role_name', 'Kaprodi')->value('id') ?? 3;

        // ---------------------------------------------------------
        // BAGIAN 1: BIKIN 3 KAPRODI BARU (SELAIN INFORMATIKA)
        // ---------------------------------------------------------
        $kaprodis = [
            ['id' => 'KAP-004', 'name' => 'Budi Arsitek, M.Ars', 'email' => 'kaprodi.arsitektur@uinsaizu.ac.id', 'unit' => 'U004'],
            ['id' => 'KAP-005', 'name' => 'Citra Lingkungan, M.Ling', 'email' => 'kaprodi.lingkungan@uinsaizu.ac.id', 'unit' => 'U005'],
            ['id' => 'KAP-006', 'name' => 'Dian Pustaka, M.IP', 'email' => 'kaprodi.pustaka@uinsaizu.ac.id', 'unit' => 'U006'],
        ];

        foreach ($kaprodis as $k) {
            DB::table('mst_user')->updateOrInsert(
                ['id' => $k['id']],
                [
                    'name' => $k['name'],
                    'email' => $k['email'],
                    'password' => Hash::make('kaprodi123'),
                    'identity_id' => '19880101' . rand(1000, 9999),
                    'user_type' => 'DSN',
                    'unit_id' => $k['unit'],
                    'is_active' => '1',
                    'created_at' => $now,
                ]
            );
            if ($roleKaprodiId) {
                DB::table('trx_user_role')->updateOrInsert(['user_id' => $k['id'], 'role_id' => $roleKaprodiId]);
            }
        }

        // ---------------------------------------------------------
        // BAGIAN 2: BIKIN 30 DOSEN BARU (Disebar ke 4 Prodi)
        // ---------------------------------------------------------
        $prodis = ['U003', 'U004', 'U005', 'U006'];
        $dosenIds = []; // Simpen ID Dosen buat nanti pasangin ke Matkul/Soal

        for ($i = 1; $i <= 30; $i++) {
            $dosenId = 'DSN-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $dosenIds[] = $dosenId;
            $unitId = $prodis[$i % 4]; // Disebar gantian (Modulus 4)

            DB::table('mst_user')->updateOrInsert(
                ['id' => $dosenId],
                [
                    'name' => 'Dosen Dummy Ke-' . $i,
                    'email' => 'dosen' . $i . '@uinsaizu.ac.id',
                    'password' => Hash::make('dosen123'),
                    'identity_id' => '19900101' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'user_type' => 'DSN',
                    'unit_id' => $unitId,
                    'is_active' => '1',
                    'created_at' => $now,
                ]
            );
            if ($roleDosenId) {
                DB::table('trx_user_role')->updateOrInsert(['user_id' => $dosenId, 'role_id' => $roleDosenId]);
            }
        }

        // ---------------------------------------------------------
        // BAGIAN 3: BIKIN 40 MATA KULIAH (10 per Prodi)
        // ---------------------------------------------------------
        $coursesData = [];
        // Kita persingkat kodenya jadi 1 huruf biar pas 5 karakter (contoh: MKI01)
        $coursePrefixes = [
            'U003' => 'I', // Informatika -> MKI01 - MKI10
            'U004' => 'A', // Arsitektur -> MKA01 - MKA10
            'U005' => 'L', // Lingkungan -> MKL01 - MKL10
            'U006' => 'P', // Perpustakaan -> MKP01 - MKP10
        ];

        foreach ($prodis as $prodi) {
            for ($c = 1; $c <= 10; $c++) {
                $coursesData[] = [
                    // Gabungin 'MK' + 1 Huruf Prodi + 2 Angka = 5 Karakter Pass!
                    'id' => 'MK' . $coursePrefixes[$prodi] . str_pad($c, 2, '0', STR_PAD_LEFT),
                    'course_name' => 'Mata Kuliah Dummy ' . $coursePrefixes[$prodi] . ' ' . $c,
                    'unit_id' => $prodi,
                    'is_active' => '1'
                ];
            }
        }

        foreach ($coursesData as $course) {
            DB::table('mst_course')->updateOrInsert(
                ['id' => $course['id']],
                $course
            );
        }

        // ---------------------------------------------------------
        // BAGIAN 4: BIKIN PENGAJUAN (PROPOSAL) & 15 SOAL TIAP MATKUL
        // ---------------------------------------------------------

        // Asumsi periode 1 = 2024/2025 Gasal
        $periodId = 1;

        // Loop semua matkul yang barusan dibuat
        foreach ($coursesData as $course) {
            $randomDosenId = $dosenIds[array_rand($dosenIds)]; // Ambil random dosen buat jadi pembuatnya

            // 1. Insert Proposal (Status APPROVED biar tampil di Bank Soal)
            $proposalId = DB::table('trx_exam_proposals')->insertGetId([
                'uuid' => Str::uuid()->toString(),
                'course_id' => $course['id'],
                'exam_type' => 'UAS', // Kita set UAS semua buat contoh
                'period_id' => $periodId,
                'status' => 'APPROVED',
                'created_by' => $randomDosenId,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // 2. Insert 15 Soal buat Proposal ini
            // Biar total bobot 100: Kita bikin 10 soal bobot 6 (total 60), 5 soal bobot 8 (total 40)
            for ($q = 1; $q <= 15; $q++) {
                $weight = ($q <= 10) ? 6 : 8;

                // Ambil 2 CPMK random buat dicantumin ke soal
                $randomCpmk = json_encode(['CP-01', 'CP-03']);

                $questionId = DB::table('trx_questions')->insertGetId([
                    'uuid' => Str::uuid()->toString(),
                    'course_id' => $course['id'],
                    'cpmk_id' => $randomCpmk,
                    'question_text' => '<p>Jelaskan konsep dasar dari materi ' . $course['course_name'] . ' pada bagian ke-' . $q . ' secara komprehensif beserta contoh penerapannya di dunia nyata!</p>',
                    'image_path' => null,
                    'created_by' => $randomDosenId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // 3. Sambungin Soal ke Proposal di tabel Pivot
                DB::table('trx_exam_questions')->insert([
                    'proposal_id' => $proposalId,
                    'question_id' => $questionId,
                    'order_no' => $q,
                    'weight' => $weight,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // 4. (Opsional) Bikin Log bahwa ini udah di-Approve sama Kaprodi
            DB::table('trx_exam_reviews')->insert([
                'proposal_id' => $proposalId,
                'reviewer_id' => 'KAP-004', // Random Kaprodi Dummy
                // 'status' => 'APPROVED',
                'comment' => 'Soal sudah sesuai standar dan di-generate dari Seeder.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}