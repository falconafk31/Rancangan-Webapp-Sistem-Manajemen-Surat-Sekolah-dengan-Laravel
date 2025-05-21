# Mockup Antarmuka Pengguna Sistem Manajemen Surat Sekolah

## Deskripsi Umum

Sistem Manajemen Surat Sekolah memerlukan antarmuka yang intuitif, responsif, dan mudah digunakan oleh berbagai pengguna dengan peran yang berbeda (admin, kepala sekolah, staff, guru). Desain antarmuka akan menggunakan pendekatan modern dengan Bootstrap 5 dan Laravel Blade untuk implementasi.

## Tema dan Warna

Tema antarmuka akan menggunakan skema warna berikut:
- **Warna Primer**: #3490dc (Biru)
- **Warna Sekunder**: #38c172 (Hijau)
- **Warna Aksen**: #f6993f (Oranye)
- **Warna Teks Utama**: #2d3748 (Abu-abu Gelap)
- **Warna Latar**: #f8fafc (Abu-abu Sangat Terang)
- **Warna Peringatan**: #e3342f (Merah)

## Struktur Halaman

Setiap halaman akan memiliki struktur umum sebagai berikut:

1. **Header**
   - Logo dan nama sekolah
   - Menu navigasi utama
   - Dropdown profil pengguna
   - Notifikasi

2. **Sidebar**
   - Menu navigasi sekunder
   - Pintasan cepat
   - Status pengguna

3. **Konten Utama**
   - Judul halaman
   - Breadcrumb
   - Konten spesifik halaman

4. **Footer**
   - Informasi hak cipta
   - Tautan cepat
   - Informasi kontak

## Mockup Halaman Utama

### 1. Halaman Login

```
+------------------------------------------+
|                                          |
|                                          |
|      +----------------------------+      |
|      |       LOGO SEKOLAH         |      |
|      +----------------------------+      |
|                                          |
|      +----------------------------+      |
|      | SISTEM MANAJEMEN SURAT SEKOLAH |  |
|      +----------------------------+      |
|                                          |
|      +----------------------------+      |
|      | Email/Username:            |      |
|      | [                        ] |      |
|      +----------------------------+      |
|                                          |
|      +----------------------------+      |
|      | Password:                  |      |
|      | [                        ] |      |
|      +----------------------------+      |
|                                          |
|      [Ingat Saya]                        |
|                                          |
|      +----------------------------+      |
|      |          LOGIN            |       |
|      +----------------------------+      |
|                                          |
|      [Lupa Password?]                    |
|                                          |
|                                          |
|      © 2025 Sistem Manajemen Surat       |
|                                          |
+------------------------------------------+
```

### 2. Dashboard

```
+------------------------------------------+
| LOGO | SISTEM MANAJEMEN SURAT | User ▼ |N|
+------+------------------------+----------+
|      |                                   |
|      | Dashboard                         |
|      |                                   |
| MENU | +------+  +------+  +------+     |
|      | |Surat |  |Surat |  |Disposisi|  |
|      | |Masuk |  |Keluar|  |        |   |
|      | |  25  |  |  12  |  |   8    |   |
|      | +------+  +------+  +------+     |
|      |                                   |
|      | Surat Terbaru                     |
|      | +-----------------------------+   |
|      | | No | Tgl | Perihal | Status |  |
|      | |----|-----|---------|--------|  |
|      | | 01 | 4/4 | Undangan| Proses |  |
|      | | 02 | 3/4 | Laporan | Selesai|  |
|      | | 03 | 2/4 | Pengumum| Baru   |  |
|      | +-----------------------------+   |
|      |                                   |
|      | Disposisi Terbaru                 |
|      | +-----------------------------+   |
|      | | No | Tgl | Dari    | Kepada |  |
|      | |----|-----|---------|--------|  |
|      | | 01 | 4/4 | Kepsek  | TU     |  |
|      | | 02 | 3/4 | Kepsek  | Guru   |  |
|      | | 03 | 2/4 | Wakasek | Staff  |  |
|      | +-----------------------------+   |
|      |                                   |
+------+-----------------------------------+
| © 2025 Sistem Manajemen Surat Sekolah    |
+------------------------------------------+
```

### 3. Halaman Daftar Surat Masuk

```
+------------------------------------------+
| LOGO | SISTEM MANAJEMEN SURAT | User ▼ |N|
+------+------------------------+----------+
|      |                                   |
|      | Surat Masuk > Daftar              |
|      |                                   |
| MENU | [+ Tambah Surat] [Import] [Export]|
|      |                                   |
|      | Filter: [         ] [Terapkan]    |
|      | Status: [Semua ▼]                 |
|      |                                   |
|      | +-----------------------------+   |
|      | | No | Tgl | Asal | Perihal |Aksi|
|      | |----|-----|------|---------|----| 
|      | | 01 | 4/4 | Dinas| Undangan| ···|
|      | | 02 | 3/4 | Wali | Izin    | ···|
|      | | 03 | 2/4 | Komit| Rapat   | ···|
|      | | 04 | 1/4 | Dinas| Pengumum| ···|
|      | | 05 | 1/4 | Kemen| Instruksi| ··|
|      | +-----------------------------+   |
|      |                                   |
|      | [< 1 2 3 ... 10 >]                |
|      |                                   |
+------+-----------------------------------+
| © 2025 Sistem Manajemen Surat Sekolah    |
+------------------------------------------+
```

### 4. Halaman Detail Surat Masuk

```
+------------------------------------------+
| LOGO | SISTEM MANAJEMEN SURAT | User ▼ |N|
+------+------------------------+----------+
|      |                                   |
|      | Surat Masuk > Detail              |
|      |                                   |
| MENU | [Edit] [Hapus] [Disposisi] [Cetak]|
|      |                                   |
|      | Informasi Surat                   |
|      | +-----------------------------+   |
|      | | No. Agenda   | SM-2025-001  |   |
|      | | No. Surat    | 123/DN/IV/25 |   |
|      | | Tanggal Surat| 01/04/2025   |   |
|      | | Tgl Diterima | 04/04/2025   |   |
|      | | Asal Surat   | Dinas Pendid.|   |
|      | | Tujuan       | Kepala Sekolah|  |
|      | | Perihal      | Undangan Rapat|  |
|      | | Klasifikasi  | Undangan     |   |
|      | | Status       | Belum Diproses|  |
|      | +-----------------------------+   |
|      |                                   |
|      | Isi Ringkas                       |
|      | +-----------------------------+   |
|      | | Undangan rapat koordinasi   |   |
|      | | pembahasan kurikulum baru   |   |
|      | | tahun ajaran 2025/2026      |   |
|      | +-----------------------------+   |
|      |                                   |
|      | Lampiran                          |
|      | +-----------------------------+   |
|      | | [Dokumen.pdf] [Lampiran.jpg]|   |
|      | +-----------------------------+   |
|      |                                   |
|      | Riwayat Disposisi                 |
|      | +-----------------------------+   |
|      | | Tgl | Dari | Kepada | Status|   |
|      | |----|------|--------|-------|   |
|      | | 4/4 |Kepsek| TU    | Proses|   |
|      | +-----------------------------+   |
|      |                                   |
+------+-----------------------------------+
| © 2025 Sistem Manajemen Surat Sekolah    |
+------------------------------------------+
```

### 5. Halaman Tambah/Edit Surat Masuk

```
+------------------------------------------+
| LOGO | SISTEM MANAJEMEN SURAT | User ▼ |N|
+------+------------------------+----------+
|      |                                   |
|      | Surat Masuk > Tambah              |
|      |                                   |
| MENU | [Simpan] [Batal]                  |
|      |                                   |
|      | Form Surat Masuk                  |
|      | +-----------------------------+   |
|      | | No. Agenda   | [Auto]      |   |
|      | | No. Surat    | [         ] |   |
|      | | Tanggal Surat| [Datepicker]|   |
|      | | Tgl Diterima | [Datepicker]|   |
|      | | Asal Surat   | [         ] |   |
|      | | Tujuan       | [Dropdown ▼]|   |
|      | | Perihal      | [         ] |   |
|      | | Klasifikasi  | [Dropdown ▼]|   |
|      | | Status       | [Dropdown ▼]|   |
|      | +-----------------------------+   |
|      |                                   |
|      | Isi Ringkas                       |
|      | +-----------------------------+   |
|      | | [                          ]|   |
|      | | [                          ]|   |
|      | | [                          ]|   |
|      | +-----------------------------+   |
|      |                                   |
|      | Upload File                       |
|      | +-----------------------------+   |
|      | | [Browse...] [Upload]        |   |
|      | +-----------------------------+   |
|      |                                   |
+------+-----------------------------------+
| © 2025 Sistem Manajemen Surat Sekolah    |
+------------------------------------------+
```

### 6. Halaman Daftar Surat Keluar

```
+------------------------------------------+
| LOGO | SISTEM MANAJEMEN SURAT | User ▼ |N|
+------+------------------------+----------+
|      |                                   |
|      | Surat Keluar > Daftar             |
|      |                                   |
| MENU | [+ Tambah Surat] [Import] [Export]|
|      |                                   |
|      | Filter: [         ] [Terapkan]    |
|      | Status: [Semua ▼]                 |
|      |                                   |
|      | +-----------------------------+   |
|      | | No | Tgl | Tujuan| Perihal |Aksi|
|      | |----|-----|-------|---------|----| 
|      | | 01 | 4/4 | Dinas | Laporan | ···|
|      | | 02 | 3/4 | Wali  | Undangan| ···|
|      | | 03 | 2/4 | Komite| Proposal| ···|
|      | | 04 | 1/4 | Vendor| Perminta| ···|
|      | | 05 | 1/4 | Dinas | Pengajua| ···|
|      | +-----------------------------+   |
|      |                                   |
|      | [< 1 2 3 ... 10 >]                |
|      |                                   |
+------+-----------------------------------+
| © 2025 Sistem Manajemen Surat Sekolah    |
+------------------------------------------+
```

### 7. Halaman Disposisi

```
+------------------------------------------+
| LOGO | SISTEM MANAJEMEN SURAT | User ▼ |N|
+------+------------------------+----------+
|      |                                   |
|      | Disposisi > Tambah                |
|      |                                   |
| MENU | [Simpan] [Batal]                  |
|      |                                   |
|      | Informasi Surat                   |
|      | +-----------------------------+   |
|      | | No. Agenda   | SM-2025-001  |   |
|      | | No. Surat    | 123/DN/IV/25 |   |
|      | | Tanggal Surat| 01/04/2025   |   |
|      | | Asal Surat   | Dinas Pendid.|   |
|      | | Perihal      | Undangan Rapat|  |
|      | +-----------------------------+   |
|      |                                   |
|      | Form Disposisi                    |
|      | +-----------------------------+   |
|      | | Dari         | [Dropdown ▼]|   |
|      | | Kepada       | [Dropdown ▼]|   |
|      | | Tanggal      | [Datepicker]|   |
|      | | Sifat        | [Dropdown ▼]|   |
|      | | Batas Waktu  | [Datepicker]|   |
|      | +-----------------------------+   |
|      |                                   |
|      | Isi Disposisi                     |
|      | +-----------------------------+   |
|      | | [                          ]|   |
|      | | [                          ]|   |
|      | +-----------------------------+   |
|      |                                   |
|      | Catatan                           |
|      | +-----------------------------+   |
|      | | [                          ]|   |
|      | +-----------------------------+   |
|      |                                   |
+------+-----------------------------------+
| © 2025 Sistem Manajemen Surat Sekolah    |
+------------------------------------------+
```

### 8. Halaman Pengaturan

```
+------------------------------------------+
| LOGO | SISTEM MANAJEMEN SURAT | User ▼ |N|
+------+------------------------+----------+
|      |                                   |
|      | Pengaturan > Umum                 |
|      |                                   |
| MENU | [Simpan] [Batal]                  |
|      |                                   |
|      | Informasi Sekolah                 |
|      | +-----------------------------+   |
|      | | Nama Sekolah  | [         ] |   |
|      | | Alamat        | [         ] |   |
|      | | Telepon       | [         ] |   |
|      | | Email         | [         ] |   |
|      | | Website       | [         ] |   |
|      | +-----------------------------+   |
|      |                                   |
|      | Logo & Kop Surat                  |
|      | +-----------------------------+   |
|      | | Logo Sekolah  | [Browse...] |   |
|      | | Kop Surat     | [Browse...] |   |
|      | +-----------------------------+   |
|      |                                   |
|      | Format Penomoran                  |
|      | +-----------------------------+   |
|      | | Surat Masuk   | [         ] |   |
|      | | Surat Keluar  | [         ] |   |
|      | +-----------------------------+   |
|      |                                   |
|      | Informasi Pimpinan                |
|      | +-----------------------------+   |
|      | | Nama Pimpinan | [         ] |   |
|      | | NIP           | [         ] |   |
|      | +-----------------------------+   |
|      |                                   |
+------+-----------------------------------+
| © 2025 Sistem Manajemen Surat Sekolah    |
+------------------------------------------+
```

### 9. Halaman Manajemen Pengguna

```
+------------------------------------------+
| LOGO | SISTEM MANAJEMEN SURAT | User ▼ |N|
+------+------------------------+----------+
|      |                                   |
|      | Pengguna > Daftar                 |
|      |                                   |
| MENU | [+ Tambah Pengguna]               |
|      |                                   |
|      | Filter: [         ] [Terapkan]    |
|      | Role: [Semua ▼]                   |
|      |                                   |
|      | +-----------------------------+   |
|      | | No | Nama | Email | Role  |Aksi|
|      | |----|------|-------|-------|----| 
|      | | 01 | Admin| admin@| Admin | ···|
|      | | 02 | Budi | budi@ | Kepsek| ···|
|      | | 03 | Ani  | ani@  | Staff | ···|
|      | | 04 | Dedi | dedi@ | Guru  | ···|
|      | +-----------------------------+   |
|      |                                   |
|      | [< 1 2 3 ... 5 >]                 |
|      |                                   |
+------+-----------------------------------+
| © 2025 Sistem Manajemen Surat Sekolah    |
+------------------------------------------+
```

### 10. Halaman Template Surat

```
+------------------------------------------+
| LOGO | SISTEM MANAJEMEN SURAT | User ▼ |N|
+------+------------------------+----------+
|      |                                   |
|      | Template > Daftar                 |
|      |                                   |
| MENU | [+ Tambah Template]               |
|      |                                   |
|      | Filter: [         ] [Terapkan]    |
|      | Jenis: [Semua ▼]                  |
|      |                                   |
|      | +-----------------------------+   |
|      | | No | Nama | Jenis | Klas. |Aksi|
|      | |----|------|-------|-------|----| 
|      | | 01 | Surat| Keluar| Undang| ···|
|      | | 02 | Surat| Keluar| Lapor.| ···|
|      | | 03 | Surat| Masuk | Pember| ···|
|      | | 04 | Surat| Keluar| Permin| ···|
|      | +-----------------------------+   |
|      |                                   |
|      | [< 1 2 >]                         |
|      |                                   |
+------+-----------------------------------+
| © 2025 Sistem Manajemen Surat Sekolah    |
+------------------------------------------+
```

## Komponen UI

### 1. Navigasi Utama
```
+------------------------------------------+
| LOGO | SISTEM MANAJEMEN SURAT | User ▼ |N|
+------------------------------------------+
```

### 2. Sidebar Menu
```
+-------------+
| Dashboard   |
+-------------+
| Surat       |
| ├ Masuk     |
| └ Keluar    |
+-------------+
| Disposisi   |
+-------------+
| Template    |
+-------------+
| Laporan     |
+-------------+
| Pengaturan  |
| ├ Umum      |
| ├ Pengguna  |
| └ Klasifikasi|
+-------------+
```

### 3. Card Statistik
```
+------------------+
| Surat Masuk      |
|                  |
|       25         |
|                  |
| +5 hari ini      |
+------------------+
```

### 4. Tabel Data
```
+-----------------------------+
| No | Tgl | Perihal | Status |
|----|-----|---------|--------|
| 01 | 4/4 | Undangan| Proses |
| 02 | 3/4 | Laporan | Selesai|
+-----------------------------+
```

### 5. Form Input
```
+-----------------------------+
| Label        | [Input    ] |
+-----------------------------+
```

### 6. Tombol Aksi
```
[Primer]  [Sekunder]  [Bahaya]
```

### 7. Pagination
```
[< 1 2 3 ... 10 >]
```

### 8. Alert/Notifikasi
```
+-----------------------------+
| ✓ Berhasil menyimpan data  |
+-----------------------------+

+-----------------------------+
| ⚠ Peringatan: Data belum   |
| lengkap                     |
+-----------------------------+

+-----------------------------+
| ✗ Gagal: Terjadi kesalahan |
+-----------------------------+
```

## Responsivitas

Desain antarmuka akan responsif dengan pendekatan mobile-first:

1. **Mobile (< 768px)**
   - Menu sidebar akan disembunyikan dan dapat diakses melalui tombol hamburger
   - Tabel akan menampilkan data dalam format kartu
   - Form input akan ditampilkan dalam satu kolom

2. **Tablet (768px - 992px)**
   - Menu sidebar dapat ditampilkan/disembunyikan
   - Tabel akan menampilkan data dalam format standar dengan scrolling horizontal jika diperlukan
   - Form input dapat ditampilkan dalam dua kolom

3. **Desktop (> 992px)**
   - Menu sidebar selalu ditampilkan
   - Tabel akan menampilkan data dalam format standar
   - Form input dapat ditampilkan dalam multi-kolom

## Teknologi Frontend

1. **Framework CSS**: Bootstrap 5
2. **JavaScript**: jQuery dan Vanilla JS
3. **Icon**: Font Awesome
4. **Form Validation**: Laravel Validation + Client-side validation
5. **Datepicker**: Flatpickr
6. **File Upload**: Dropzone.js
7. **WYSIWYG Editor**: TinyMCE
8. **Data Tables**: DataTables.js
9. **Charts**: Chart.js

## Alur Pengguna (User Flow)

### Alur Login
1. Pengguna mengakses sistem
2. Sistem menampilkan halaman login
3. Pengguna memasukkan email/username dan password
4. Sistem memvalidasi kredensial
5. Jika valid, pengguna diarahkan ke dashboard
6. Jika tidak valid, sistem menampilkan pesan error

### Alur Surat Masuk
1. Pengguna mengakses menu Surat Masuk
2. Sistem menampilkan daftar surat masuk
3. Pengguna dapat:
   - Melihat detail surat dengan mengklik baris data
   - Menambah surat baru dengan mengklik tombol Tambah
   - Mengubah surat dengan mengklik tombol Edit
   - Menghapus surat dengan mengklik tombol Hapus
   - Membuat disposisi dengan mengklik tombol Disposisi

### Alur Disposisi
1. Pengguna mengakses menu Disposisi atau mengklik tombol Disposisi pada detail surat
2. Sistem menampilkan form disposisi
3. Pengguna mengisi form disposisi
4. Sistem menyimpan disposisi
5. Sistem mengirim notifikasi kepada penerima disposisi

## Pertimbangan Aksesibilitas

1. Kontras warna yang cukup untuk teks dan latar belakang
2. Ukuran font yang dapat dibaca dengan baik
3. Label yang jelas untuk form input
4. Pesan error yang informatif
5. Navigasi keyboard yang konsisten
6. Atribut alt untuk gambar
7. ARIA labels untuk komponen interaktif
