# Sainteku

Sainteku adalah Sistem Informasi Terpadu Fakultas Sains dan Teknologi UIN Prof. K.H. Saifuddin Zuhri Purwokerto. Aplikasi dibangun dengan Laravel modular untuk mengelola layanan akademik, master data kampus, repositori dokumen, prestasi, infrastruktur, berita, dan profil pengguna.

## Stack

- PHP 8.2+
- Laravel 12
- `widart/laravel-modules`
- MySQL/MariaDB
- Vite 7
- Tailwind CSS 4
- Alpine.js 3
- Font Awesome 7

## Fitur Utama

### Core
- Landing page fakultas.
- Berita kampus hasil scraping via command/job.
- Auth, reset password, profil pengguna.
- Dashboard role-based.
- Sidebar/menu modular berbasis `mst_menu`.
- Role/permission berbasis modul (`mst_role`, `mst_module`, `ref_permission`, `trx_role_permission`).
- Notifikasi internal untuk workflow approval.

### Master Data
- User/admin CRUD, status aktif, unit, tipe user, assign role.
- Role CRUD dan matriks permission per modul.
- Unit organisasi CRUD: universitas, fakultas, program studi.
- Infrastruktur/inventaris CRUD: kategori, stok, satuan, harga, unit pemilik, status, foto, deskripsi, preview gambar, filter/search/pagination.
- Mata kuliah CRUD: kode otomatis `MK001`, fakultas → prodi cascade, filter/search AJAX, pagination JSON.
- Data pendukung akademik dan referensi master.

### Monev Akademik
- Bank soal.
- Workspace tashih soal.
- Pengajuan ujian/soal oleh dosen.
- Review/tashih soal.
- Review Kaprodi.
- Log perubahan/revisi soal.

### Document Repository
- Dashboard statistik dokumen.
- Upload dokumen dengan tipe/unit/keterangan/file.
- Review dokumen oleh approver.
- Status pending/approved/rejected/revisi.
- Download dokumen.
- Revisi file.
- Notifikasi approver.

### Manajemen Achievement
- Prestasi mahasiswa.
- Prestasi dosen.
- Portofolio.
- Referensi kategori/tingkat prestasi.

### Manajemen Infrastruktur
- Dashboard fasilitas/aset.
- Pengajuan peminjaman fasilitas.
- Persetujuan/penolakan peminjaman.
- Status pending, disetujui, ditolak, dikembalikan.
- Pemulihan stok saat pengembalian.

### News Scraper
- Model `ScrapedNews`.
- Command `ScrapeNewsCommand`.
- Job `ScrapeNewsJob`.
- Integrasi landing/blog section.

## Instalasi Dev

```bash
git clone https://github.com/Anggaputra9/sainteku.git
cd sainteku
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Konfigurasi DB di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sainteku
DB_USERNAME=root
DB_PASSWORD=
```

Migrasi dan seeder:

```bash
php artisan migrate --seed
```

Storage link:

```bash
php artisan storage:link
```

Jalankan aplikasi:

```bash
npm run dev
php artisan serve
```

Akses: http://127.0.0.1:8000

## Build Production

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Permission server:

```bash
chmod -R 775 storage bootstrap/cache
```

## Struktur Penting

```text
app/                          Core app, auth, dashboard, news, notification
app/Console/Commands/          Command scraper
app/Jobs/                      Queue job scraper
Modules/MasterData/            User, role, unit, course, infrastructure
Modules/MonevAkademik/         Bank soal, tashih, proposal, review
Modules/DocumentRepository/    Repositori dokumen dan approval
Modules/ManajemenAchievement/  Prestasi dan portofolio
Modules/ManajementInfrastruktur/ Peminjaman fasilitas/aset
resources/views/               Layout, landing, dashboard
routes/web.php                 Route utama
```

## Route Penting

### Master Data
- `/masterdata/admin/users`
- `/masterdata/roles`
- `/masterdata/units`
- `/masterdata/infrastructures`
- `/masterdata/courses`
- `/masterdata/courses/api/prodis`
- `/masterdata/courses/api/data`

### Monev Akademik
- `/monevakademik/bank-soal`
- `/monevakademik/tashih`
- `/monevakademik/dosen/exam`
- `/monevakademik/kaprodi/review`

### Document Repository
- `/documentrepository/dashboard`
- `/documentrepository`
- `/documentrepository/review`

### Manajemen Infrastruktur
- `/manajementinfrastruktur`
- `/manajementinfrastruktur/pengajuan`
- `/manajementinfrastruktur/persetujuan`

## Akun Seeder

Seeder membuat akun awal (lihat `database/seeders/DatabaseSeeder.php`):

- Admin: `admin@sainteku.ac.id` / `password`
- Dosen: `arifianilhamnurriandana@gmail.com` / `Argtgbgt`
- Kaprodi: `anas@uinsaizu.ac.id` / `kaprodi`
- Mahasiswa: `niamilah@uinsaizu.ac.id` / `password`

## Catatan Dev

- Gunakan `php artisan module:list` untuk cek module aktif.
- Upload infrastruktur memakai disk `public`; wajib `php artisan storage:link`.
- Mata kuliah memakai ID otomatis format `MK001` dst.
- Infrastruktur memakai ID otomatis format `I0001` dst.
- Permission umum: `C`, `R`, `U`, `D`, `A`, `V` sesuai data `ref_permission`.

## Testing Cepat

```bash
php artisan route:list
php artisan test
npm run build
```

## Lisensi

Internal/project kampus. Sesuaikan lisensi repo sebelum distribusi publik.
