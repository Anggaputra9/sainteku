# Sainteku

Sainteku adalah aplikasi berbasis Laravel untuk pengelolaan layanan akademik dan administrasi di lingkungan fakultas, dengan struktur modular (nwidart/laravel-modules).

## Fitur Utama
- **Autentikasi & Profil**: login, reset password, dan manajemen profil pengguna.
- **Master Data**:
  - Manajemen pengguna & role/akses
  - Unit/Prodi
  - Kurikulum
  - Kategori berkas
  - Infrastruktur
  - Mata kuliah (courses)
- **Repositori Dokumen**:
  - Dashboard statistik
  - Unggah & unduh dokumen
  - Review/approve & revisi dokumen
- **Monev Akademik**:
  - Tashih soal (pengajuan, review/approve, revisi)
  - Bank soal repository
- **Manajemen Prestasi**:
  - Prestasi mahasiswa (CRUD & download)
  - Repositori prestasi dosen
  - Portofolio
  - Approval admin untuk prestasi
- **Manajemen Infrastruktur**:
  - Pengajuan peminjaman
  - Persetujuan/ACC peminjaman
- **Manajemen Event** (CRUD)
- **Pengaduan Mahasiswa** (CRUD)
- **Penjaminan Mutu Akademik** (CRUD)
- **Pelaporan** (CRUD)

## Prasyarat
- PHP **8.2+**
- Composer
- Node.js + npm
- Database (MySQL/MariaDB atau SQLite)
- Ekstensi PHP umum untuk Laravel: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, Fileinfo

## Instalasi Lokal
1. **Install dependencies**
   ```bash
   composer install
   npm install
   ```
2. **Buat file `.env`**
   - Jika ada `.env.example`, salin:
     ```bash
     cp .env.example .env
     ```
   - Jika tidak ada, buat `.env` sendiri dan isi minimal:
     - `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_URL`
     - `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
3. **Generate app key**
   ```bash
   php artisan key:generate
   ```
4. **Migrasi & seeding data**
   ```bash
   php artisan migrate --seed
   ```
5. **Storage link (untuk upload file)**
   ```bash
   php artisan storage:link
   ```
6. **Build asset**
   ```bash
   npm run build
   ```
7. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```
   Alternatif dev mode (server + queue + vite):
   ```bash
   composer run dev
   ```

## Akun Default (Administrator)
Data default admin dibuat saat seeding.

- **Email**: `admin@sainteku.ac.id`
- **Password**: `password`

> ⚠️ **PERINGATAN KEAMANAN:** Password default sangat lemah. Wajib ganti setelah login pertama, dan untuk produksi ubah di seeder atau terapkan reset password wajib di aplikasi.

## Akses Modul (Contoh URL)
- Dashboard: `http://localhost:8000/dashboard`
- Master Data: `http://localhost:8000/masterdata`
- Repositori Dokumen: `http://localhost:8000/documentrepository`
- Monev Akademik: `http://localhost:8000/monev-akademik`
- Manajemen Infrastruktur: `http://localhost:8000/manajementinfrastruktur`

## Deployment
1. **Siapkan server produksi** (PHP 8.2+, Composer, Node.js untuk build).
2. **Set `.env` produksi**:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://domain-anda`
   - Konfigurasi database & mail/queue jika digunakan.
3. **Install dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   npm run build
   ```
4. **Migrasi database**
   ```bash
   php artisan migrate --force
   ```
   Jika ingin data awal/admin:
   ```bash
   php artisan db:seed --force
   ```
5. **Storage & cache**
   ```bash
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
6. **Web server**
   - Arahkan document root ke folder `public/`.
   - Pastikan permission `storage/` dan `bootstrap/cache/` bisa ditulis.
7. **Background worker (opsional)**
   - Queue: `php artisan queue:work`
   - Scheduler: tambahkan cron untuk `php artisan schedule:run`
