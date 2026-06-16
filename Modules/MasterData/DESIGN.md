# Design System — Modul Master Data (Sainteku)

Dokumen ini merangkum **bahasa desain** modul Master Data: pola visual, interaksi, struktur halaman, dan kontrak teknis yang dipakai secara konsisten di seluruh fitur. Gunakan sebagai referensi utama saat membangun atau mereview halaman baru.

**Stack:** Laravel Blade · Alpine.js 3 · Tailwind CSS 4 · Font Awesome 7  
**Layout global:** `resources/views/layouts/app.blade.php`  
**Namespace view:** `masterdata::`

> Spesifikasi mendalam halaman Users ada di [`DESIGN_ADMIN_USERS.md`](./DESIGN_ADMIN_USERS.md).

---

## 1. Identitas Visual

### Karakter desain

Master Data Sainteku mengadopsi gaya **admin panel modern-institutional**:

- Bersih, padat informasi, tanpa dekorasi berlebihan
- Aksen **indigo** sebagai warna modul (CTA, ikon judul, FAB, pagination maju)
- **Blue** untuk aksi sekunder/informatif (tombol Detail, badge role)
- Card putih dengan border halus + `shadow-sm` — tidak flat total, tidak heavy shadow
- Dark mode penuh di semua komponen (`dark:` variant konsisten)
- Bahasa UI: **Bahasa Indonesia** (label, placeholder, empty state, alert)

### Tone interaksi

| Aspek | Pola |
|-------|------|
| Kecepatan | Tabel dimuat AJAX — tanpa full page reload saat filter/pagination |
| Kejelasan | Satu aksi primer di tabel: **Detail** |
| Kedalaman | Create / Edit / Hapus masuk modal, bukan halaman terpisah |
| Filter | Search + per-page di toolbar; filter lanjutan di FAB pojok kanan bawah |

---

## 2. Design Tokens

### Warna

| Token | Tailwind | Pemakaian |
|-------|----------|-----------|
| **Primary** | `indigo-500` / `indigo-600` / `indigo-700` | Ikon judul halaman, CTA Tambah, FAB filter, pagination Next |
| **Action / Detail** | `blue-50` · `blue-600` · `blue-700` | Tombol Detail di tabel, badge role, ikon modal detail |
| **Success** | `green-50` · `green-600` · `green-700` | Status aktif, alert sukses |
| **Danger** | `red-50` · `red-600` · `red-700` | Status nonaktif, hapus, reset filter, alert error |
| **Info / Unit** | `indigo-50` · `purple-50` | Kode unit, badge prodi |
| **Surface** | `white` · `gray-800` | Card toolbar, tabel |
| **Surface muted** | `gray-50` · `#0f172a` | Input background, modal body dark |
| **Modal header** | `white` · `#1e293b` | Header sticky modal |
| **Modal body** | `slate-50` · `#0f172a` | Area scroll modal |

### Warna aksen per entitas (dashboard & avatar)

Halaman CRUD memakai avatar lingkaran dengan inisial. Warna bervariasi per konteks:

| Entitas | Avatar | Badge khusus |
|---------|--------|--------------|
| User | `bg-blue-100 text-blue-600` | Role: `bg-blue-50` |
| Mata Kuliah | `bg-indigo-100 text-indigo-600` | Prodi: `bg-purple-50` |
| Role | `bg-purple-100 text-purple-600` | Permission count |
| Unit | `bg-teal-100 text-teal-600` | Tipe unit |
| Infrastruktur | `bg-amber-100 text-amber-600` | Tipe barang |

Dashboard index memakai warna berbeda per kartu menu (blue, green, purple, yellow, teal, red) — ini khusus halaman overview, bukan halaman CRUD.

### Border radius

| Elemen | Class |
|--------|-------|
| Card, input, tombol sekunder | `rounded-xl` |
| Modal container | `rounded-2xl` |
| Badge, status pill | `rounded-full` |
| FAB | `rounded-full` (`h-14 w-14`) |
| Tombol Detail di tabel | `rounded-lg` |

### Tipografi

| Elemen | Class |
|--------|-------|
| Judul halaman | `text-2xl font-bold text-gray-900 dark:text-white` |
| Breadcrumb parent | `text-sm font-medium text-gray-500` |
| Breadcrumb aktif | `text-indigo-600 dark:text-indigo-400` |
| Label form (modal) | `text-[10px] font-bold uppercase tracking-widest text-gray-400` |
| Header section modal | `text-sm font-bold text-gray-700 dark:text-gray-200` |
| Header tabel | `text-xs uppercase font-semibold` |
| Kode/ID | `font-mono font-semibold text-indigo-700` |

### Spacing & layout

| Pola | Class |
|------|-------|
| Root halaman CRUD | `space-y-6` |
| Padding section | `p-4` (toolbar) · `px-6 py-4` (sel tabel) |
| Gap header | `gap-4 pb-4 border-b border-gray-200` |
| Grid dashboard stat | `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5` |
| Grid menu dashboard | `grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6` |

### Input standar

```html
class="w-full rounded-xl border-gray-300 bg-gray-50 py-2.5 pl-11 pr-4 text-sm
       outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10
       dark:bg-[#0f172a] dark:border-gray-600 dark:text-white"
```

Search input selalu punya ikon `fa-search` di `left-4`.

### Select standar

```html
class="rounded-xl border-gray-300 bg-gray-50 px-3 py-2.5 text-sm
       outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10
       dark:bg-[#0f172a] dark:border-gray-600 dark:text-white"
```

---

## 3. Anatomi Halaman

### 3.1 Halaman CRUD (pola utama)

Semua halaman manajemen data mengikuti struktur yang sama:

```
┌─────────────────────────────────────────────────────────────┐
│ HEADER                                                      │
│  [ikon indigo] Judul Halaman          [+ Tambah] (indigo)   │
│  Master Data / {Submenu}                                    │
├─────────────────────────────────────────────────────────────┤
│ ALERT (Alpine, auto-hide 4s)                                │
├─────────────────────────────────────────────────────────────┤
│ TOOLBAR CARD (rounded-xl border shadow-sm)                  │
│  [🔍 Search ........................] [Tampilkan ▼ 50]      │
├─────────────────────────────────────────────────────────────┤
│ TABLE CARD (rounded-xl border shadow-sm)                    │
│  thead: uppercase bg-gray-50                                │
│  tbody: divide-y, hover:bg-gray-50                          │
├─────────────────────────────────────────────────────────────┤
│ PAGINATION                                                  │
│  Menampilkan X–Y dari Z data          [Prev] [Next]         │
└─────────────────────────────────────────────────────────────┘

  @include modal-create, modal-detail, delete-modal

                                    ┌──────────────┐
                                    │ Filter panel │  ← FAB (teleport body)
                                    └──────────────┘
                                         ( ● )      ← dot merah jika filter aktif
```

**Root Alpine:**

```html
<div class="space-y-6" x-data="{entity}App()" x-init="initData()" x-cloak>
```

**Halaman yang sudah mengikuti pola ini:**

| Halaman | File index | Alpine component |
|---------|-----------|------------------|
| Pengguna | `admin/users.blade.php` | `usersApp()` |
| Role | `roles/index.blade.php` | `rolesApp()` |
| Unit | `units/index.blade.php` | `unitsApp()` |
| Mata Kuliah | `courses/index.blade.php` | `coursesApp()` |
| Infrastruktur | `infrastructures/index.blade.php` | `infrastructuresApp()` |

### 3.2 Dashboard Master Data (`index.blade.php`)

Halaman overview — **tidak** memakai pola CRUD/Alpine tabel.

**Struktur:**

1. Header + breadcrumb (tanpa CTA)
2. Alert session (Blade `@if`, bukan Alpine)
3. **Stat cards** — 4 kolom: Total Pengguna, Role, Unit, Kurikulum
4. **Menu grid** — kartu navigasi ke setiap sub-fitur dengan:
   - Hover border berwarna per kartu
   - Badge status singkat
   - Link "Kelola {Fitur}" dengan panah SVG
5. **Tips box** — `bg-blue-50 border-blue-200` dengan checklist onboarding

---

## 4. Komponen UI

### 4.1 Header halaman

```html
<div class="flex flex-col gap-4 pb-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
  <div>
    <h2 class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
      <i class="fa-solid fa-{icon} text-indigo-500"></i> Manajemen {Entitas}
    </h2>
    <nav>
      <ol class="flex items-center gap-2 mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
        <li>Master Data /</li>
        <li class="text-indigo-600 dark:text-indigo-400">{Submenu}</li>
      </ol>
    </nav>
  </div>
  <button type="button"
    @click="window.dispatchEvent(new CustomEvent('open-create-modal', { bubbles: true }))"
    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white transition bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700">
    <i class="fa-solid fa-plus"></i> Tambah
  </button>
</div>
```

**Ikon per halaman:**

| Halaman | Ikon FA |
|---------|---------|
| Pengguna | `fa-users` |
| Role | `fa-shield-halved` |
| Unit | `fa-building` |
| Mata Kuliah | `fa-book-open` |
| Infrastruktur | `fa-boxes-stacked` |

### 4.2 Alert

Dua mekanisme:

| Konteks | Implementasi |
|---------|--------------|
| Halaman CRUD | Alpine `alert: { type, message }` — auto-hide 4 detik |
| Dashboard index | Blade `@if(session('success/error'))` |

```html
<template x-if="alert.message">
  <div class="flex items-center gap-3 p-4 border-l-4 rounded-r-lg shadow-sm"
    :class="alert.type === 'error'
      ? 'border-red-500 bg-red-50 text-red-700'
      : 'border-green-500 bg-green-50 text-green-700'">
    <i class="fa-solid" :class="alert.type === 'error' ? 'fa-circle-xmark' : 'fa-check-circle'"></i>
    <span class="text-sm font-bold" x-text="alert.message"></span>
  </div>
</template>
```

### 4.3 Tabel

**Prinsip:** Kolom aksi hanya **Detail** — tidak ada Edit/Hapus di baris.

| State | Tampilan |
|-------|----------|
| Loading | `fa-circle-notch fa-spin text-indigo-600` + "Memuat data..." |
| Kosong | Ikon entitas opacity-50 + pesan "{Entitas} tidak ditemukan." |
| Data | `hover:bg-gray-50 dark:hover:bg-gray-700/30` |

**Tombol Detail:**

```html
<button type="button" @click="openDetail(item)"
  class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700
         border border-blue-200 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400
         dark:border-blue-800 transition shadow-sm">
  <i class="fa-solid fa-eye"></i> Detail
</button>
```

**Status pill (Aktif/Nonaktif):**

```html
<!-- Aktif -->
<span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium
             text-green-700 border border-green-200">
  <span class="w-1.5 h-1.5 bg-green-600 rounded-full"></span> Aktif
</span>
```

### 4.4 Pagination

- Teks: `Menampilkan {from} – {to} dari {total} data`
- **Prev:** `bg-gray-200 rounded-xl` — disabled `opacity-50`
- **Next:** `bg-indigo-600 rounded-xl text-white`
- Ganti halaman → `window.scrollTo({ top: 0, behavior: 'smooth' })`

Opsi per-page: `10 | 25 | 50 | 100 | 150 | 250` — default **`50`** (courses default sort `name_asc`).

### 4.5 FAB Filter

Filter sekunder dipindah ke **Floating Action Button** agar toolbar tidak penuh.

| Properti | Nilai |
|----------|-------|
| Posisi | `fixed; bottom: 1.5rem; right: 1.5rem` |
| Teleport | `body` via `x-teleport` |
| Z-index | `z-[9990]` |
| Ukuran tombol | `h-14 w-14 rounded-full bg-indigo-600` |
| Ikon buka | `fa-sliders` |
| Ikon tutup | `fa-xmark` |
| Panel | `w-72 rounded-2xl shadow-2xl` di atas FAB |

**Indikator filter aktif:**
- Dot merah `8px` di pojok kanan atas ikon (bukan tengah tombol)
- Muncul jika `activeFilterCount > 0`
- Tooltip: `{n} filter aktif`

**Header panel:** Judul "Filter" + tombol **Reset** (merah, uppercase kecil) — hanya tampil jika ada filter aktif.

**Filter per halaman:**

| Halaman | Filter FAB | Default sort |
|---------|-----------|--------------|
| Users | Urutkan, Status, Role, Unit | `newest` |
| Roles | Urutkan, Status | `name_asc` |
| Units | Urutkan, Tipe, Status | `name_asc` |
| Courses | Urutkan, Fakultas, Prodi, Status | `name_asc` |
| Infrastruktur | Urutkan, Tipe, Unit, Status | `name_asc` |

**Cascade filter:** Courses — pilih Fakultas → fetch prodi via API → enable select Prodi.

---

## 5. Sistem Modal

### 5.1 Infrastruktur teknis

Semua modal di-`x-teleport` ke `#modal-root` di `layouts/app.blade.php`:

```html
<div id="modal-root"></div>
```

CSS global:

```css
#modal-root { z-index: 10000000; pointer-events: none; }
#modal-root > * { pointer-events: auto; }
.app-modal-overlay { z-index: 10000000 !important; }
```

### 5.2 Jenis modal

| Modal | Ukuran | Trigger event |
|-------|--------|---------------|
| Create | `max-w-4xl` · `max-h-[90dvh]` | `open-create-modal` |
| Detail (view/edit) | `max-w-4xl` · `max-h-[90dvh]` | `open-detail-modal` |
| Delete | `max-w-md` | `open-delete-modal` |
| Permissions (Role) | `max-w-5xl` | `open-perm-modal` |

### 5.3 Anatomi modal standar

```
┌─────────────────────────────────────┐
│ HEADER (sticky top, shrink-0)       │
│  [ikon box 8×8/9×9] Judul + subtitle│
├─────────────────────────────────────┤
│ BODY (flex-1 overflow-y-auto)     │
│  ┌─ Section Card ─────────────────┐ │
│  │ [ikon] Judul Section           │ │
│  │ border-b header                │ │
│  │ grid fields p-5                │ │
│  └────────────────────────────────┘ │
├─────────────────────────────────────┤
│ FOOTER (sticky bottom, shrink-0)    │
│  [Secondary kiri]  [Primary kanan]  │
└─────────────────────────────────────┘
```

**Overlay:** `backdrop-blur-sm bg-gray-900/40`  
**Animasi:** `scale-95 → scale-100` · `opacity-0 → opacity-100` · duration 300ms  
**Tutup:** `@click.away` pada panel modal

### 5.4 Section card (dalam modal)

```html
<div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-[#1e293b]">
  <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 flex items-center gap-2">
      <i class="fa-solid fa-user text-indigo-500"></i> {Judul Section}
    </h4>
  </div>
  <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
    <!-- fields -->
  </div>
</div>
```

### 5.5 Mode Detail: Lihat ↔ Edit

Satu modal detail mendukung dua mode (`editMode` boolean):

| Mode | Footer kiri | Footer kanan |
|------|-------------|--------------|
| **Lihat** | Tutup (gray) | Hapus (red, opsional) + Edit (blue) |
| **Edit** | Batal (gray, restore snapshot) | Simpan (blue, submit) |

Hapus membuka modal delete terpisah — modal detail ditutup dulu agar tidak overlap.

### 5.6 Modal Create — tab Manual / Bulk

Halaman Users dan Courses mendukung import bulk:

| Tab | Metode submit |
|-----|---------------|
| Manual | Form POST klasik |
| Bulk | Fetch JSON + event `{entity}-bulk-imported` |

Footer create: `[Batal]` `[Simpan]`

### 5.7 Custom Events

| Event | Emitter | Payload kunci |
|-------|---------|---------------|
| `open-create-modal` | Tombol Tambah | — |
| `open-detail-modal` | Tombol Detail | `url`, `deleteUrl`, `{entity}Data`, `canDelete` |
| `open-delete-modal` | Tombol Hapus | `url`, `name` |
| `open-perm-modal` | Tombol Permission (Role) | `url`, `name`, `assigned[]` |
| `{entity}-bulk-imported` | Bulk import selesai | `success_count`, `message` |

---

## 6. Pola Alpine.js

### 6.1 Struktur component

```js
document.addEventListener('alpine:init', () => {
  Alpine.data('{entity}App', () => ({
    // state
    searchQuery: '',
    perPageFilter: '50',
    sortFilter: '{default}',
    // ...filter fields
    filterFabOpen: false,
    {entity}List: [],
    isLoading: false,
    pagination: {},
    alert: { type: '', message: '' },

    get activeFilterCount() { /* hitung filter non-default */ },

    initData() {
      // flash session → alert
      this.fetch{Entity}();
    },

    flash(type, message) {
      this.alert = { type, message };
      setTimeout(() => { this.alert.message = ''; }, 4000);
    },

    async fetch{Entity}(page = 1) { /* AJAX ke api/data */ },
    changePage(page) { /* fetch + scroll top */ },
    resetFilters() { /* reset + fetch(1) */ },
    openDetail(item) { /* dispatch open-detail-modal */ },
  }));
});
```

### 6.2 Kontrak API data

Setiap halaman CRUD punya endpoint:

```
GET /masterdata/{resource}/api/data
```

| Param umum | Tipe | Keterangan |
|------------|------|------------|
| `page` | int | Halaman |
| `per_page` | int | 10–250 |
| `search` | string | Full-text |
| `sort` | string | Sesuai entitas |
| `status` | `0\|1` | Filter aktif/nonaktif |

**Response:** Laravel pagination JSON (`data`, `current_page`, `from`, `to`, `total`, `prev_page_url`, `next_page_url`)

**Setiap item** wajib menyertakan URL aksi:

```json
{
  "update_url": "/masterdata/.../id",
  "delete_url": "/masterdata/.../id",
  "can_delete": true
}
```

### 6.3 Fetch headers

```js
headers: {
  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
  'Accept': 'application/json',
}
```

Debounce search: `@input.debounce.400ms`

---

## 7. Struktur File

### Konvensi penamaan per entitas

```
Modules/MasterData/resources/views/{entity}/
├── index.blade.php          # Halaman utama (Alpine app)
├── modal-create.blade.php   # Modal tambah
├── modal-detail.blade.php   # Modal lihat/edit
└── delete-modal.blade.php   # Konfirmasi hapus

Modules/MasterData/app/Http/Controllers/
└── {Entity}Controller.php   # index + api/data + CRUD
```

**Role** tambahan: `modal-permissions.blade.php`, `modal-edit.blade.php`

### Extends layout

Semua halaman:

```blade
@extends('layouts.app')

@section('content')
  ...
@endsection
```

> Modul punya `components/layouts/master.blade.php` (slot-based) tetapi halaman produksi memakai `layouts.app` global.

---

## 8. Prinsip UX yang Wajib Diikuti

### ✅ Lakukan

- Satu tombol **Detail** di kolom aksi tabel
- Edit dan Hapus hanya dari modal Detail
- Teleport semua modal ke `#modal-root`
- Komunikasi antar komponen via `CustomEvent`
- Loading state sebelum data tampil (kosongkan list saat fetch)
- Empty state dengan ikon + pesan jelas
- Dark mode di setiap elemen interaktif
- Label form uppercase kecil (`text-[10px] tracking-widest`)
- Section card di dalam modal untuk mengelompokkan field

### ❌ Jangan

- Tombol Edit/Hapus langsung di setiap baris tabel
- Halaman terpisah untuk form create/edit (kecuali legacy route yang belum dihapus)
- Semua filter di toolbar atas (membuat UI sesak)
- Modal tanpa teleport (z-index bentrok dengan sidebar)
- Full page reload untuk pagination/filter
- Warna CTA random di luar palet indigo/blue

---

## 9. Variasi per Halaman

| Fitur khusus | Halaman |
|--------------|---------|
| Tab Manual + Bulk import | Users, Courses |
| Modal matriks permission | Roles (`modal-permissions`) |
| Cascade Fakultas → Prodi | Courses (filter + create) |
| Preview gambar | Infrastruktur |
| Lock role admin utama | Users (`ADM-UIN-0000001`) |
| Auto-role mahasiswa | Users (tipe MHS) |

---

## 10. Checklist Halaman Baru

Saat menambah halaman master data:

- [ ] `extends('layouts.app')`
- [ ] Root `x-data="{entity}App()"` + `x-init="initData()"` + `x-cloak`
- [ ] Header: ikon indigo + breadcrumb `Master Data / {X}` + tombol Tambah
- [ ] Alert Alpine dengan auto-hide 4 detik
- [ ] Toolbar card: search debounce 400ms + select per-page
- [ ] Tabel AJAX: loading / empty / data states
- [ ] Kolom aksi: hanya tombol Detail (`fa-eye`)
- [ ] Pagination Prev/Next + teks range
- [ ] FAB filter dengan `activeFilterCount` + dot merah
- [ ] `modal-create.blade.php` — teleport `#modal-root`
- [ ] `modal-detail.blade.php` — mode lihat/edit
- [ ] `delete-modal.blade.php` — konfirmasi terpisah
- [ ] API `GET .../api/data` dengan pagination JSON
- [ ] Custom events: `open-create-modal`, `open-detail-modal`, `open-delete-modal`
- [ ] Section card konsisten di modal
- [ ] Footer modal: secondary kiri, primary kanan

---

## 11. Referensi Visual Cepat

### Tombol

| Varian | Class utama |
|--------|-------------|
| CTA Tambah | `bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700` |
| Detail (tabel) | `bg-blue-50 border-blue-200 text-blue-700 rounded-lg` |
| Primary (modal) | `bg-blue-600 rounded-xl font-bold` |
| Secondary | `bg-gray-200 rounded-xl font-bold dark:bg-gray-700` |
| Danger | `bg-red-600 rounded-xl font-bold shadow-sm` |
| Pagination Prev | `bg-gray-200 rounded-xl` |
| Pagination Next | `bg-indigo-600 rounded-xl text-white` |

### Komponen legacy (jarang dipakai)

`resources/views/components/buttons/action.blade.php` — button preset lama (emerald/amber). Halaman CRUD produksi **tidak** memakai komponen ini; mereka inline Tailwind sesuai tabel di atas.

---

## 12. Peta Halaman Modul

| Route | Status UI | Pola desain |
|-------|-----------|-------------|
| `/masterdata` | ✅ Dashboard | Stat cards + menu grid |
| `/masterdata/admin/users` | ✅ Lengkap | CRUD + FAB + Bulk |
| `/masterdata/roles` | ✅ Lengkap | CRUD + Permission matrix |
| `/masterdata/units` | ✅ Lengkap | CRUD + FAB |
| `/masterdata/courses` | ✅ Lengkap | CRUD + FAB + Bulk + Cascade |
| `/masterdata/infrastructures` | ✅ Lengkap | CRUD + FAB + Preview |
| `/masterdata/curricula` | 🚧 Placeholder | Coming soon |
| `/masterdata/categories` | 🚧 Placeholder | Coming soon |

---

## 13. Dokumen Terkait

| File | Isi |
|------|-----|
| [`DESIGN_ADMIN_USERS.md`](./DESIGN_ADMIN_USERS.md) | Spesifikasi lengkap halaman Users (flow, API, bulk import) |
| [`SIDEBAR_IMPLEMENTATION_DOCS.md`](../../SIDEBAR_IMPLEMENTATION_DOCS.md) | Navigasi sidebar modul |
| [`CLAUDE.md`](../../CLAUDE.md) | Panduan dev umum proyek Sainteku |

---

## Changelog

| Tanggal | Perubahan |
|---------|-----------|
| 2026-06-16 | Dokumen awal — ekstraksi pola desain dari Users, Roles, Units, Courses, Infrastruktur, Dashboard |

---

*Dokumen ini mengacu pada branch `ar`, Laravel 12.51.0, Tailwind CSS 4.*