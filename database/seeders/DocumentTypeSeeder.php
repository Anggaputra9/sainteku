<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. ISI DATA KATEGORI (Gunakan ID 4 karakter: C001)
        $categories = [
            ['id' => 'C001', 'description' => 'Akademik & Kemahasiswaan'],
            ['id' => 'C002', 'description' => 'Kepegawaian & SDM'],
            ['id' => 'C003', 'description' => 'Keuangan & Aset'],
            ['id' => 'C004', 'description' => 'Penelitian & Pengabdian'],
            ['id' => 'C005', 'description' => 'Umum & Tata Usaha'],
        ];

        foreach ($categories as $category) {
            DB::table('ref_document_category')->updateOrInsert(
                ['id' => $category['id']],
                ['description' => $category['description']]
            );
        }

        // 2. ISI DATA TIPE DOKUMEN (Gunakan ID 4 karakter: T001)
        $types = [
            // Tipe Dokumen Akademik (C001)
            ['id' => 'T001', 'category' => 'C001', 'description' => 'Surat Keputusan (SK) Rektor'],
            ['id' => 'T002', 'category' => 'C001', 'description' => 'Buku Pedoman Akademik'],
            ['id' => 'T003', 'category' => 'C001', 'description' => 'Standar Operasional Prosedur (SOP)'],
            ['id' => 'T004', 'category' => 'C001', 'description' => 'Kurikulum Program Studi'],

            // Tipe Dokumen Kepegawaian (C002)
            ['id' => 'T005', 'category' => 'C002', 'description' => 'Surat Tugas'],
            ['id' => 'T006', 'category' => 'C002', 'description' => 'Peraturan Kepegawaian'],

            // Tipe Dokumen Keuangan (C003)
            ['id' => 'T007', 'category' => 'C003', 'description' => 'Rencana Anggaran Belanja (RAB)'],
            ['id' => 'T008', 'category' => 'C003', 'description' => 'Laporan Pertanggungjawaban (LPJ)'],

            // Tipe Dokumen Penelitian (C004)
            ['id' => 'T009', 'category' => 'C004', 'description' => 'Proposal Penelitian'],
            ['id' => 'T010', 'category' => 'C004', 'description' => 'Laporan Akhir Pengabdian'],

            // Tipe Dokumen Umum (C005)
            ['id' => 'T011', 'category' => 'C005', 'description' => 'Memorandum of Understanding (MoU)'],
            ['id' => 'T012', 'category' => 'C005', 'description' => 'Surat Edaran'],
        ];

        foreach ($types as $type) {
            DB::table('ref_document_type')->updateOrInsert(
                ['id' => $type['id']],
                [
                    'category' => $type['category'], 
                    'description' => $type['description']
                ]
            );
        }

        // 3. TAMBAHAN: Isi Workflow Status
        DB::table('mst_workflow_status')->updateOrInsert(
            ['id' => 1],
            ['description' => 'Draft / Aktif', 'is_active' => '1', 'created_at' => now()]
        );
        DB::table('mst_workflow_status')->updateOrInsert(
            ['id' => 2],
            ['description' => 'Menunggu Persetujuan', 'is_active' => '1', 'created_at' => now()]
        );
        DB::table('mst_workflow_status')->updateOrInsert(
            ['id' => 3],
            ['description' => 'Disetujui', 'is_active' => '1', 'created_at' => now()]
        );
        DB::table('mst_workflow_status')->updateOrInsert(
            ['id' => 4],
            ['description' => 'Ditolak', 'is_active' => '1', 'created_at' => now()]
        );
    }
}