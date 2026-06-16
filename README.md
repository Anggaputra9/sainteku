# Sainteku

Sainteku adalah Sistem Informasi Terpadu Fakultas Sains dan Teknologi UIN Prof. K.H. Saifuddin Zuhri Purwokerto. Aplikasi dibangun dengan Laravel modular untuk mengelola layanan akademik, master data kampus, repositori dokumen, prestasi, infrastruktur, berita, dan profil pengguna.

## Stack

- PHP 8.2+
- Laravel 12.51.0
- `widart/laravel-modules` 12.0
- MySQL/MariaDB
- Vite 7
- Tailwind CSS 4
- Alpine.js 3.15.8
- Font Awesome 7.2.0
- Axios 1.13.6
- Prism.js 1.30.0
- Signature Pad 5.1.3
- DomPDF 3.1 (PDF generation)
- [Whatsar](https://github.com/arifianilhamnrr/whatsar) 0.1 (WhatsApp gateway self-hosted, proses terpisah)

## Fitur Utama

### Core
- Landing page fakultas.
- Berita kampus hasil scraping via command/job.
- Auth, reset password (email + WhatsApp), profil pengguna.
- Lupa password dari landing: lookup **email / NIP / NIM**, link reset ke email terdaftar + WA (jika `phone_number` ada).
- Dashboard role-based.
- Sidebar/menu modular berbasis `mst_menu`.
- Role/permission berbasis modul (`mst_role`, `mst_module`, `ref_permission`, `trx_role_permission`).
- Notifikasi internal untuk workflow approval.
- Notifikasi WhatsApp opsional via `NotifService` (`send_whatsapp => true`).

### WhatsApp Gateway (Whatsar)
- Self-hosted WhatsApp API (bind `127.0.0.1:8080`), dikelola dari panel admin Sainteku.
- Halaman **Pengaturan Aplikasi → WhatsApp**: kelola session, scan QR, status live, kirim pesan uji.
- Driver `WHATSAPP_DRIVER=whatsar|fonnte|log` untuk rollback/dev.
- Modul dengan notif WA aktif: Monev Akademik, Prestasi, Document Repository, Infrastruktur.
- Detail teknis: [`docs/whatsar/PLAN.md`](docs/whatsar/PLAN.md), [`docs/whatsar/ROADMAP.md`](docs/whatsar/ROADMAP.md).

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

### Manajemen Event
- Pengelolaan event kampus.
- Pendaftaran dan tracking peserta event.
- Jadwal dan lokasi event.

### Pengaduan Mahasiswa
- Sistem pengaduan/complaint mahasiswa.
- Tracking status pengaduan.
- Notifikasi dan follow-up pengaduan.

### Penjaminan Mutu Akademik
- Monitoring dan evaluasi mutu akademik.
- Audit internal akademik.
- Laporan penjaminan mutu.

### Pelaporan
- Dashboard laporan terpadu.
- Export laporan ke berbagai format.
- Statistik dan analitik data.

### Ujian
- Manajemen ujian online.
- Bank soal dan pengaturan ujian.
- Penilaian dan hasil ujian.
- Integrasi dengan sistem akademik.

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

Atau gunakan composer script untuk development (dengan queue, logs, dan vite):

```bash
composer dev
```

Akses: http://127.0.0.1:8000

### Whatsar (opsional, untuk notif & reset password via WA)

Setelah `.env` dikonfigurasi, install binary + systemd:

```bash
sudo bash scripts/whatsar-install.sh
```

Variabel penting di `.env`:

```env
WHATSAPP_DRIVER=whatsar
WHATSAPP_ENABLED=true
WHATSAR_URL=http://127.0.0.1:8080
WHATSAR_API_KEY=<api-key-dari-install-script>
```

Cek service:

```bash
systemctl status whatsar
curl -s http://127.0.0.1:8080/health
```

Pairing session WA: login sebagai admin → **Pengaturan Aplikasi → WhatsApp** → Tambah Session → scan QR.

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
app/                                Core app, auth, dashboard, news, notification
app/Services/WhatsarClient.php      HTTP client ke Whatsar API
app/Services/WhatsappService.php    Kirim pesan WA (Whatsar/Fonnte/log)
app/Http/Controllers/Settings/      Pengaturan email, AI, WhatsApp
config/whatsapp.php                 Driver & konfigurasi WhatsApp
scripts/whatsar-install.sh          Install binary Whatsar + systemd
deploy/whatsar.service              Unit systemd Whatsar
docs/whatsar/                       Roadmap & rencana integrasi
resources/views/settings/whatsapp/  UI admin session & QR pairing
app/Console/Commands/               Command scraper
app/Jobs/                           Queue job scraper
Modules/MasterData/                 User, role, unit, course, infrastructure
Modules/MonevAkademik/              Bank soal, tashih, proposal, review
Modules/DocumentRepository/         Repositori dokumen dan approval
Modules/ManajemenAchievement/       Prestasi dan portofolio
Modules/ManajementInfrastruktur/    Peminjaman fasilitas/aset
Modules/ManajementEvent/            Manajemen event kampus
Modules/PengaduanMahasiswa/         Sistem pengaduan mahasiswa
Modules/PenjaminanMutuAkademik/     Penjaminan mutu akademik
Modules/Pelaporan/                  Dashboard dan laporan terpadu
Modules/Ujian/                      Sistem ujian online
resources/views/                    Layout, landing, dashboard
routes/web.php                      Route utama
```

## Route Penting

### Pengaturan Aplikasi
- `/settings/email`
- `/settings/ai`
- `/settings/whatsapp` — kelola session WhatsApp (admin)

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

### Manajemen Event
- `/manajementevent`
- `/manajementevent/events`
- `/manajementevent/participants`

### Pengaduan Mahasiswa
- `/pengaduanmahasiswa`
- `/pengaduanmahasiswa/complaints`
- `/pengaduanmahasiswa/tracking`

### Penjaminan Mutu Akademik
- `/penjaminanmutuakademik`
- `/penjaminanmutuakademik/audit`
- `/penjaminanmutuakademik/reports`

### Pelaporan
- `/pelaporan`
- `/pelaporan/dashboard`
- `/pelaporan/export`

### Ujian
- `/ujian`
- `/ujian/exams`
- `/ujian/results`

## Akun Seeder

Seeder membuat akun awal (lihat `database/seeders/DatabaseSeeder.php`):

- Admin: `admin@sainteku.ac.id` / `password`
- Dosen: `arifianilhamnurriandana@gmail.com` / `Argtgbgt`
- Kaprodi: `anas@uinsaizu.ac.id` / `kaprodi`
- Mahasiswa: `niamilah@uinsaizu.ac.id` / `password`

## Catatan Dev

- WhatsApp butuh session **connected** di `/settings/whatsapp` dan user punya `phone_number` di profil agar notif/reset password via WA terkirim.
- Gagal kirim WA tidak membatalkan notif in-app/email (graceful fail + log).
- Whatsar data disimpan di `storage/whatsar/` (di-ignore git).
- Gunakan `php artisan module:list` untuk cek module aktif.
- Upload infrastruktur memakai disk `public`; wajib `php artisan storage:link`.
- Mata kuliah memakai ID otomatis format `MK001` dst.
- Infrastruktur memakai ID otomatis format `I0001` dst.
- Permission umum: `C`, `R`, `U`, `D`, `A`, `V` sesuai data `ref_permission`.
- Gunakan `composer dev` untuk menjalankan server, queue, logs, dan vite secara bersamaan.
- Queue listener diperlukan untuk job scraping news dan notifikasi.
- Gunakan `php artisan pail` untuk monitoring logs real-time.

## Testing Cepat

```bash
# Cek semua route
php artisan route:list

# Jalankan test suite
php artisan test

# Build production assets
npm run build

# Cek module aktif
php artisan module:list

# Setup lengkap (install, migrate, build)
composer setup
```

## Lisensi

Internal/project kampus. Sesuaikan lisensi repo sebelum distribusi publik.

## Kontributor

Project ini dikembangkan oleh tim Fakultas Sains dan Teknologi UIN Prof. K.H. Saifuddin Zuhri Purwokerto.

### Tim Developer

- **Arifian Ilham Nur Riandana**
  - Email: arifianilhamnurriandana@gmail.com
  - GitHub: [@arifianilhamnrr](https://github.com/arifianilhamnrr)

- **Angga Putra Pratama**
  - GitHub: [@Anggaputra9](https://github.com/Anggaputra9)

- **Niamilah Nabil Syahputra**
  - Email: syahputranabil521@gmail.com
  - GitHub: [@genzabis](https://github.com/genzabis)

## Changelog

### 2026-06
- Integrasi Whatsar sebagai WhatsApp gateway self-hosted
- Halaman admin `/settings/whatsapp` (session, QR pairing, polling live)
- Notifikasi WA di Monev Akademik, Prestasi, Document Repository, Infrastruktur
- Reset password via email + WhatsApp; lupa password pakai email/NIP/NIM

### 2026-05
- Update UI masterdata users
- Update UI email settings
- Update UI modal delete master data users
- Penambahan fitur settings app dan ujian
- Improve landing page dengan Tailwind CSS
- Penambahan scraping news section

### Modul Tersedia
- ✅ Master Data
- ✅ Monev Akademik
- ✅ Document Repository
- ✅ Manajemen Achievement
- ✅ Manajemen Infrastruktur
- ✅ Manajemen Event
- ✅ Pengaduan Mahasiswa
- ✅ Penjaminan Mutu Akademik
- ✅ Pelaporan
- ✅ Ujian
