# Skema Database Sistem Manajemen Surat Sekolah

## Deskripsi Sistem
Sistem Manajemen Surat Sekolah adalah aplikasi web yang dirancang untuk mengelola dokumen surat masuk dan surat keluar di lingkungan sekolah. Sistem ini memungkinkan pengguna untuk mencatat, melacak, mendisposisikan, dan mengarsipkan surat-surat resmi sekolah.

## Entitas dan Tabel

### 1. Users (Pengguna)
Tabel untuk menyimpan data pengguna sistem.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | bigint | Primary Key, Auto Increment |
| name | varchar(255) | Nama lengkap pengguna |
| email | varchar(255) | Email pengguna (unique) |
| password | varchar(255) | Password terenkripsi |
| role | enum('admin', 'kepala_sekolah', 'staff', 'guru') | Peran pengguna dalam sistem |
| jabatan | varchar(255) | Jabatan pengguna di sekolah |
| remember_token | varchar(100) | Token untuk fitur "remember me" |
| created_at | timestamp | Waktu pembuatan record |
| updated_at | timestamp | Waktu update terakhir record |

### 2. Klasifikasi Surat
Tabel untuk mengkategorikan jenis-jenis surat.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | bigint | Primary Key, Auto Increment |
| kode | varchar(20) | Kode klasifikasi surat |
| nama | varchar(255) | Nama klasifikasi surat |
| keterangan | text | Keterangan tambahan |
| created_at | timestamp | Waktu pembuatan record |
| updated_at | timestamp | Waktu update terakhir record |

### 3. Surat Masuk
Tabel untuk menyimpan data surat yang diterima oleh sekolah.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | bigint | Primary Key, Auto Increment |
| no_agenda | varchar(100) | Nomor agenda surat masuk |
| no_surat | varchar(100) | Nomor surat |
| tanggal_surat | date | Tanggal surat dibuat |
| tanggal_terima | date | Tanggal surat diterima |
| asal_surat | varchar(255) | Asal/pengirim surat |
| tujuan | varchar(255) | Tujuan surat |
| perihal | varchar(255) | Perihal/subjek surat |
| isi_ringkas | text | Ringkasan isi surat |
| klasifikasi_id | bigint | Foreign Key ke tabel Klasifikasi |
| file_path | varchar(255) | Path file surat yang diupload |
| status | enum('belum_diproses', 'sedang_diproses', 'selesai') | Status pemrosesan surat |
| created_by | bigint | Foreign Key ke tabel Users |
| updated_by | bigint | Foreign Key ke tabel Users |
| created_at | timestamp | Waktu pembuatan record |
| updated_at | timestamp | Waktu update terakhir record |

### 4. Surat Keluar
Tabel untuk menyimpan data surat yang dikeluarkan oleh sekolah.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | bigint | Primary Key, Auto Increment |
| no_agenda | varchar(100) | Nomor agenda surat keluar |
| no_surat | varchar(100) | Nomor surat |
| tanggal_surat | date | Tanggal surat dibuat |
| tanggal_kirim | date | Tanggal surat dikirim |
| tujuan | varchar(255) | Tujuan/penerima surat |
| perihal | varchar(255) | Perihal/subjek surat |
| isi_ringkas | text | Ringkasan isi surat |
| klasifikasi_id | bigint | Foreign Key ke tabel Klasifikasi |
| file_path | varchar(255) | Path file surat yang diupload |
| status | enum('draft', 'menunggu_persetujuan', 'disetujui', 'ditolak', 'dikirim') | Status surat keluar |
| created_by | bigint | Foreign Key ke tabel Users |
| updated_by | bigint | Foreign Key ke tabel Users |
| created_at | timestamp | Waktu pembuatan record |
| updated_at | timestamp | Waktu update terakhir record |

### 5. Disposisi
Tabel untuk menyimpan data disposisi surat masuk.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | bigint | Primary Key, Auto Increment |
| surat_masuk_id | bigint | Foreign Key ke tabel Surat Masuk |
| dari_user_id | bigint | Foreign Key ke tabel Users (pemberi disposisi) |
| kepada_user_id | bigint | Foreign Key ke tabel Users (penerima disposisi) |
| tanggal_disposisi | date | Tanggal disposisi dibuat |
| isi_disposisi | text | Isi/instruksi disposisi |
| sifat | enum('biasa', 'segera', 'sangat_segera', 'rahasia') | Sifat disposisi |
| batas_waktu | date | Batas waktu penyelesaian |
| status | enum('belum_dibaca', 'dibaca', 'diproses', 'selesai') | Status disposisi |
| catatan | text | Catatan tambahan |
| created_at | timestamp | Waktu pembuatan record |
| updated_at | timestamp | Waktu update terakhir record |

### 6. Lampiran
Tabel untuk menyimpan data lampiran surat.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | bigint | Primary Key, Auto Increment |
| surat_masuk_id | bigint | Foreign Key ke tabel Surat Masuk (nullable) |
| surat_keluar_id | bigint | Foreign Key ke tabel Surat Keluar (nullable) |
| nama_file | varchar(255) | Nama file lampiran |
| file_path | varchar(255) | Path file lampiran |
| ukuran_file | integer | Ukuran file dalam bytes |
| tipe_file | varchar(50) | Tipe/ekstensi file |
| keterangan | text | Keterangan tambahan |
| created_by | bigint | Foreign Key ke tabel Users |
| created_at | timestamp | Waktu pembuatan record |
| updated_at | timestamp | Waktu update terakhir record |

### 7. Template Surat
Tabel untuk menyimpan template surat yang dapat digunakan kembali.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | bigint | Primary Key, Auto Increment |
| nama | varchar(255) | Nama template |
| jenis | enum('surat_masuk', 'surat_keluar') | Jenis template |
| klasifikasi_id | bigint | Foreign Key ke tabel Klasifikasi |
| konten | text | Konten template dalam format HTML |
| created_by | bigint | Foreign Key ke tabel Users |
| updated_by | bigint | Foreign Key ke tabel Users |
| created_at | timestamp | Waktu pembuatan record |
| updated_at | timestamp | Waktu update terakhir record |

### 8. Log Aktivitas
Tabel untuk mencatat aktivitas pengguna dalam sistem.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | bigint | Primary Key, Auto Increment |
| user_id | bigint | Foreign Key ke tabel Users |
| aktivitas | varchar(255) | Deskripsi aktivitas |
| modul | varchar(100) | Modul yang diakses |
| ip_address | varchar(45) | Alamat IP pengguna |
| user_agent | text | User agent browser |
| created_at | timestamp | Waktu pembuatan record |

### 9. Pengaturan
Tabel untuk menyimpan pengaturan sistem.

| Kolom | Tipe Data | Keterangan |
|-------|-----------|------------|
| id | bigint | Primary Key, Auto Increment |
| nama_sekolah | varchar(255) | Nama sekolah |
| alamat | text | Alamat sekolah |
| telepon | varchar(20) | Nomor telepon sekolah |
| email | varchar(255) | Email sekolah |
| website | varchar(255) | Website sekolah |
| logo | varchar(255) | Path file logo sekolah |
| kop_surat | varchar(255) | Path file kop surat |
| format_nomor_surat | varchar(255) | Format penomoran surat |
| pimpinan_nama | varchar(255) | Nama pimpinan/kepala sekolah |
| pimpinan_nip | varchar(50) | NIP pimpinan/kepala sekolah |
| updated_by | bigint | Foreign Key ke tabel Users |
| updated_at | timestamp | Waktu update terakhir record |

## Relasi Antar Tabel

1. **Users** - **Surat Masuk**:
   - One-to-Many: Satu user dapat membuat/mengupdate banyak surat masuk

2. **Users** - **Surat Keluar**:
   - One-to-Many: Satu user dapat membuat/mengupdate banyak surat keluar

3. **Users** - **Disposisi**:
   - One-to-Many (dari): Satu user dapat memberikan banyak disposisi
   - One-to-Many (kepada): Satu user dapat menerima banyak disposisi

4. **Users** - **Lampiran**:
   - One-to-Many: Satu user dapat mengupload banyak lampiran

5. **Users** - **Template Surat**:
   - One-to-Many: Satu user dapat membuat/mengupdate banyak template surat

6. **Users** - **Log Aktivitas**:
   - One-to-Many: Satu user dapat memiliki banyak log aktivitas

7. **Klasifikasi** - **Surat Masuk**:
   - One-to-Many: Satu klasifikasi dapat digunakan oleh banyak surat masuk

8. **Klasifikasi** - **Surat Keluar**:
   - One-to-Many: Satu klasifikasi dapat digunakan oleh banyak surat keluar

9. **Klasifikasi** - **Template Surat**:
   - One-to-Many: Satu klasifikasi dapat digunakan oleh banyak template surat

10. **Surat Masuk** - **Disposisi**:
    - One-to-Many: Satu surat masuk dapat memiliki banyak disposisi

11. **Surat Masuk** - **Lampiran**:
    - One-to-Many: Satu surat masuk dapat memiliki banyak lampiran

12. **Surat Keluar** - **Lampiran**:
    - One-to-Many: Satu surat keluar dapat memiliki banyak lampiran

## Diagram ERD

```
+-------------+       +----------------+       +----------------+
|    Users    |       | Surat Masuk    |       |   Disposisi    |
+-------------+       +----------------+       +----------------+
| id          |<----->| created_by     |       | id             |
| name        |       | updated_by     |<----->| surat_masuk_id |
| email       |       | klasifikasi_id |       | dari_user_id   |<----+
| password    |       | ...            |       | kepada_user_id |<-+  |
| role        |       +----------------+       | ...            |  |  |
| ...         |                                +----------------+  |  |
+-------------+                                                    |  |
      ^                                                            |  |
      |           +----------------+                               |  |
      |           | Surat Keluar   |                               |  |
      +---------->| created_by     |                               |  |
      |           | updated_by     |                               |  |
      |           | klasifikasi_id |                               |  |
      |           | ...            |                               |  |
      |           +----------------+                               |  |
      |                                                            |  |
      |           +----------------+                               |  |
      |           |   Lampiran     |                               |  |
      +---------->| created_by     |                               |  |
      |           | surat_masuk_id |                               |  |
      |           | surat_keluar_id|                               |  |
      |           | ...            |                               |  |
      |           +----------------+                               |  |
      |                                                            |  |
      |           +----------------+       +----------------+      |  |
      |           | Template Surat |       |  Klasifikasi   |      |  |
      +---------->| created_by     |       | id             |      |  |
      |           | updated_by     |<----->| kode           |      |  |
      |           | klasifikasi_id |       | nama           |      |  |
      |           | ...            |       | ...            |      |  |
      |           +----------------+       +----------------+      |  |
      |                                                            |  |
      |           +----------------+                               |  |
      |           | Log Aktivitas  |                               |  |
      +---------->| user_id        |                               |  |
      |           | ...            |                               |  |
      |           +----------------+                               |  |
      |                                                            |  |
      |           +----------------+                               |  |
      |           |  Pengaturan    |                               |  |
      +---------->| updated_by     |                               |  |
                  | ...            |                               |  |
                  +----------------+                               |  |
                                                                   |  |
                                                                   |  |
                  +-------------+                                  |  |
                  |    Users    |                                  |  |
                  +-------------+                                  |  |
                  | id          |<---------------------------------+  |
                  | name        |                                     |
                  | email       |<------------------------------------+
                  | ...         |
                  +-------------+
```

## Indeks dan Optimasi

Untuk meningkatkan performa query, beberapa indeks akan ditambahkan:

1. Indeks pada kolom `email` di tabel `Users`
2. Indeks pada kolom `no_surat` di tabel `Surat Masuk` dan `Surat Keluar`
3. Indeks pada kolom `tanggal_surat` dan `tanggal_terima` di tabel `Surat Masuk`
4. Indeks pada kolom `tanggal_surat` dan `tanggal_kirim` di tabel `Surat Keluar`
5. Indeks pada kolom `surat_masuk_id` di tabel `Disposisi`
6. Indeks pada kolom `dari_user_id` dan `kepada_user_id` di tabel `Disposisi`
7. Indeks pada kolom `surat_masuk_id` dan `surat_keluar_id` di tabel `Lampiran`

## Keamanan Data

1. Password pengguna akan dienkripsi menggunakan algoritma bcrypt
2. Implementasi middleware untuk kontrol akses berdasarkan role
3. Validasi input untuk mencegah SQL injection
4. Penggunaan prepared statements untuk query database
5. Implementasi CSRF protection untuk form
6. Logging aktivitas pengguna untuk audit trail