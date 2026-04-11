<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [

            /*
            |--------------------------------------------------------------------------
            | 0. DASHBOARD UTAMA
            |--------------------------------------------------------------------------
            */
            [
                'id' => 100,
                'menu_name' => 'Dashboard',
                'menu_link' => 'dashboard', // pastikan route ini ada
                'menu_icon' => 'fa-solid fa-border-all',
                'parent_id' => null,
                'order_no' => 0,
                'is_active' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | 1. MASTER DATA
            |--------------------------------------------------------------------------
            */
            [
                'id' => 1,
                'menu_name' => 'Master Data',
                'menu_link' => null,
                'menu_icon' => 'fa-solid fa-database',
                'parent_id' => null,
                'order_no' => 1,
                'is_active' => 1,
            ],

            ['id' => 2, 'menu_name' => 'Dashboard Master', 'menu_link' => 'masterdata.index', 'menu_icon' => null, 'parent_id' => 1, 'order_no' => 1, 'is_active' => 1],
            ['id' => 3, 'menu_name' => 'Data Pengguna (User)', 'menu_link' => 'masterdata.admin.users.index', 'menu_icon' => null, 'parent_id' => 1, 'order_no' => 2, 'is_active' => 1],
            ['id' => 4, 'menu_name' => 'Data Role / Akses', 'menu_link' => 'masterdata.roles.index', 'menu_icon' => null, 'parent_id' => 1, 'order_no' => 3, 'is_active' => 1],
            ['id' => 5, 'menu_name' => 'Data Unit / Prodi', 'menu_link' => 'masterdata.units.index', 'menu_icon' => null, 'parent_id' => 1, 'order_no' => 4, 'is_active' => 1],
            ['id' => 6, 'menu_name' => 'Data Kurikulum', 'menu_link' => 'masterdata.curricula.index', 'menu_icon' => null, 'parent_id' => 1, 'order_no' => 7, 'is_active' => 1],
            ['id' => 7, 'menu_name' => 'Data Kategori Berkas', 'menu_link' => 'masterdata.categories.index', 'menu_icon' => null, 'parent_id' => 1, 'order_no' => 6, 'is_active' => 1],
            ['id' => 8, 'menu_name' => 'Data Infrastruktur', 'menu_link' => 'masterdata.infrastructures.index', 'menu_icon' => null, 'parent_id' => 1, 'order_no' => 5, 'is_active' => 1],

            /*
            |--------------------------------------------------------------------------
            | 2. MANAJEMEN DOKUMEN
            |--------------------------------------------------------------------------
            */
            [
                'id' => 10,
                'menu_name' => 'Manajemen Dokumen',
                'menu_link' => null,
                'menu_icon' => 'fa-solid fa-folder-open',
                'parent_id' => null,
                'module_id' => 1,
                'order_no' => 2,
                'is_active' => 1,
            ],

            ['id' => 11, 'menu_name' => 'Repository Dokumen', 'menu_link' => 'DocumentRepository.index', 'menu_icon' => null, 'parent_id' => 10, 'module_id' => 1, 'order_no' => 2, 'is_active' => 1],
            ['id' => 12, 'menu_name' => 'Dashboard Dokumen', 'menu_link' => 'DocumentRepository.dashboard.index', 'menu_icon' => null, 'parent_id' => 10, 'module_id' => 1, 'order_no' => 1, 'is_active' => 1],

            /*
            |--------------------------------------------------------------------------
            | 3. MONEV AKADEMIK
            |--------------------------------------------------------------------------
            */
            [
                'id' => 20,
                'menu_name' => 'Monev Akademik',
                'menu_link' => null,
                'menu_icon' => 'fa-solid fa-file-lines',
                'parent_id' => null,
                'module_id' => 3,
                'order_no' => 3,
                'is_active' => 1,
            ],
            // UPDATE: Mengarahkan menu ke rute yang tepat
            ['id' => 21, 'menu_name' => 'Tashih Soal', 'menu_link' => 'monevakademik.tashih.index', 'parent_id' => 20, 'module_id' => 2, 'order_no' => 1, 'is_active' => 1],
            ['id' => 22, 'menu_name' => 'Bank Soal', 'menu_link' => 'monevakademik.banksoal.index', 'parent_id' => 20, 'module_id' => 2, 'order_no' => 2, 'is_active' => 1],
            // END UPDATE
            ['id' => 23, 'menu_name' => 'Monev Perkuliahan', 'menu_link' => '#', 'parent_id' => 20, 'module_id' => 3, 'order_no' => 3, 'is_active' => 1],
            ['id' => 24, 'menu_name' => 'Survey Kepuasan', 'menu_link' => '#', 'parent_id' => 20, 'module_id' => 3, 'order_no' => 4, 'is_active' => 1],

            /*
            |--------------------------------------------------------------------------
            | 4. MANAJEMEN EVENT
            |--------------------------------------------------------------------------
            */
            [
                'id' => 30,
                'menu_name' => 'Manajemen Event',
                'menu_link' => '#',
                'menu_icon' => 'fa-solid fa-calendar-days',
                'parent_id' => null,
                'order_no' => 4,
                'is_active' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | 5. MANAJEMEN ACHIEVEMENT
            |--------------------------------------------------------------------------
            */
            [
                'id' => 40,
                'menu_name' => 'Manajemen Prestasi',
                'menu_link' => null,
                'menu_icon' => 'fa-solid fa-trophy',
                'parent_id' => null,
                'module_id' => 5,
                'order_no' => 5,
                'is_active' => 1,
            ],

            ['id' => 41, 'menu_name' => 'Prestasi Mahasiswa', 'menu_link' => '#', 'parent_id' => 40, 'module_id' => 5, 'order_no' => 1, 'is_active' => 1],
            ['id' => 42, 'menu_name' => 'Repository Prestasi Dosen', 'menu_link' => '#', 'parent_id' => 40, 'module_id' => 5, 'order_no' => 2, 'is_active' => 1],
            ['id' => 43, 'menu_name' => 'Portofolio User', 'menu_link' => '#', 'parent_id' => 40, 'module_id' => 5, 'order_no' => 3, 'is_active' => 1],
            ['id' => 44, 'menu_name' => 'Approval Prestasi Mahasiswa', 'menu_link' => '#', 'parent_id' => 40, 'module_id' => 5, 'order_no' => 4, 'is_active' => 1],
            ['id' => 45, 'menu_name' => 'Approval Prestasi Dosen', 'menu_link' => '#', 'parent_id' => 40, 'module_id' => 5, 'order_no' => 5, 'is_active' => 1],

            /*
            |--------------------------------------------------------------------------
            | 6. PENGADUAN MAHASISWA
            |--------------------------------------------------------------------------
            */
            [
                'id' => 50,
                'menu_name' => 'Pengaduan Mahasiswa',
                'menu_link' => '#',
                'menu_icon' => 'fa-solid fa-bullhorn',
                'parent_id' => null,
                'order_no' => 6,
                'is_active' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | 7. MANAJEMEN INFRASTRUKTUR
            |--------------------------------------------------------------------------
            */
            [
                'id' => 60,
                'menu_name' => 'Infrastruktur',
                'menu_link' => null,
                'menu_icon' => 'fa-solid fa-building',
                'parent_id' => null,
                'module_id' => 6,
                'order_no' => 7,
                'is_active' => 1,
            ],

            ['id' => 61, 'menu_name' => 'Dashboard', 'menu_link' => 'manajementinfrastruktur.dashboard', 'parent_id' => 60, 'module_id' => 6, 'order_no' => 1, 'is_active' => 1],
            ['id' => 62, 'menu_name' => 'Peminjaman', 'menu_link' => 'manajementinfrastruktur.pengajuan.index', 'parent_id' => 60, 'module_id' => 6, 'order_no' => 2, 'is_active' => 1],
            ['id' => 63, 'menu_name' => 'Persetujuan', 'menu_link' => 'manajementinfrastruktur.persetujuan.index', 'parent_id' => 60, 'module_id' => 6, 'order_no' => 3, 'is_active' => 1],
            /*
            |--------------------------------------------------------------------------
            | 8. PENJAMINAN MUTU AKADEMIK
            |--------------------------------------------------------------------------
            */
            [
                'id' => 70,
                'menu_name' => 'Penjaminan Mutu Akademik',
                'menu_link' => '#',
                'menu_icon' => 'fa-solid fa-school',
                'parent_id' => null,
                'module_id' => 4,
                'order_no' => 8,
                'is_active' => 1,
            ],

            /*
            |--------------------------------------------------------------------------
            | 9. PELAPORAN
            |--------------------------------------------------------------------------
            */
            [
                'id' => 80,
                'menu_name' => 'Pelaporan',
                'menu_link' => null,
                'menu_icon' => 'fa-solid fa-flag',
                'parent_id' => null,
                'order_no' => 9,
                'is_active' => 1,
            ],

            ['id' => 81, 'menu_name' => 'Status Review Soal', 'menu_link' => '#', 'parent_id' => 80, 'order_no' => 1, 'is_active' => 1],
            ['id' => 82, 'menu_name' => 'Status Monev Perkuliahan', 'menu_link' => '#', 'parent_id' => 80, 'order_no' => 2, 'is_active' => 1],
            ['id' => 83, 'menu_name' => 'Dokumen Expired', 'menu_link' => '#', 'parent_id' => 80, 'order_no' => 3, 'is_active' => 1],
            ['id' => 84, 'menu_name' => 'Statistik Achievement', 'menu_link' => '#', 'parent_id' => 80, 'order_no' => 4, 'is_active' => 1],
            ['id' => 85, 'menu_name' => 'Trend Prestasi Mahasiswa', 'menu_link' => '#', 'parent_id' => 80, 'order_no' => 5, 'is_active' => 1],
        ];

        foreach ($menus as $menu) {
            DB::table('mst_menu')->updateOrInsert(
                ['id' => $menu['id']],
                $menu
            );
        }
    }
}