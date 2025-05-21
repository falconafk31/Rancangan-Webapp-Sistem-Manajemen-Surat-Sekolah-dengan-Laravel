# Dokumentasi Sistem Manajemen Surat Sekolah

## Daftar Isi
1. [Pendahuluan](#pendahuluan)
2. [Arsitektur Sistem](#arsitektur-sistem)
3. [Struktur Database](#struktur-database)
4. [Fitur Sistem](#fitur-sistem)
5. [Alur Kerja](#alur-kerja)
6. [Keamanan](#keamanan)
7. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
8. [Persyaratan Sistem](#persyaratan-sistem)
9. [Panduan Instalasi](#panduan-instalasi)

## Pendahuluan

Sistem Manajemen Surat Sekolah adalah aplikasi berbasis web yang dirancang untuk mengelola administrasi surat menyurat di lingkungan sekolah. Sistem ini memungkinkan pengelolaan surat masuk, surat keluar, disposisi, dan klasifikasi surat secara digital, sehingga meningkatkan efisiensi dan mengurangi penggunaan kertas.

Aplikasi ini dikembangkan menggunakan framework Laravel dengan pendekatan MVC (Model-View-Controller) untuk memisahkan logika bisnis, tampilan, dan kontrol alur aplikasi. Sistem ini mendukung berbagai peran pengguna dengan hak akses yang berbeda, memungkinkan pengelolaan surat yang terstruktur sesuai dengan hierarki organisasi sekolah.

## Arsitektur Sistem

Sistem Manajemen Surat Sekolah menggunakan arsitektur MVC (Model-View-Controller) yang merupakan pola desain standar dalam framework Laravel:

### Model
Model merepresentasikan struktur data dan logika bisnis aplikasi. Model berinteraksi dengan database dan menangani validasi data. Model utama dalam sistem ini meliputi:
- User (Pengguna)
- Klasifikasi (Klasifikasi Surat)
- SuratMasuk (Surat Masuk)
- SuratKeluar (Surat Keluar)
- Disposisi (Disposisi Surat)
- Lampiran (Lampiran Surat)
- TemplateSurat (Template Surat)
- LogAktivitas (Log Aktivitas Pengguna)
- Pengaturan (Pengaturan Sistem)

### View
View menangani tampilan antarmuka pengguna (UI) yang dilihat oleh pengguna. View diimplementasikan menggunakan Blade, template engine bawaan Laravel, yang memungkinkan pembuatan tampilan yang dinamis. Struktur view utama:
- layouts/ - Template dasar yang digunakan di seluruh aplikasi
- auth/ - Halaman autentikasi (login, profil)
- dashboard/ - Halaman dashboard
- surat/masuk/ - Halaman untuk manajemen surat masuk
- surat/keluar/ - Halaman untuk manajemen surat keluar
- disposisi/ - Halaman untuk manajemen disposisi
- klasifikasi/ - Halaman untuk manajemen klasifikasi
- pengaturan/ - Halaman untuk pengaturan sistem

### Controller
Controller menangani logika aplikasi dan bertindak sebagai perantara antara Model dan View. Controller menerima input dari pengguna, memproses data menggunakan Model, dan mengembalikan output ke View. Controller utama:
- AuthController - Menangani autentikasi pengguna
- DashboardController - Menangani tampilan dashboard
- SuratMasukController - Menangani operasi CRUD untuk surat masuk
- SuratKeluarController - Menangani operasi CRUD untuk surat keluar
- DisposisiController - Menangani operasi CRUD untuk disposisi
- KlasifikasiController - Menangani operasi CRUD untuk klasifikasi
- PengaturanController - Menangani pengaturan sistem

### Middleware
Middleware berfungsi sebagai lapisan perantara antara request HTTP dan aplikasi. Middleware digunakan untuk:
- Autentikasi - Memverifikasi bahwa pengguna telah login
- Otorisasi - Memeriksa apakah pengguna memiliki hak akses yang sesuai
- Logging - Mencatat aktivitas pengguna

### Alur Request
1. Pengguna mengirim request HTTP ke server
2. Request melewati middleware yang sesuai
3. Route mengarahkan request ke controller yang tepat
4. Controller berinteraksi dengan model untuk mengambil atau memperbarui data
5. Controller merender view dengan data yang diperoleh
6. Response dikirim kembali ke pengguna

## Struktur Database

Sistem Manajemen Surat Sekolah menggunakan database relasional dengan struktur tabel sebagai berikut:

### Tabel Users
Menyimpan data pengguna sistem.
- id (primary key)
- name (nama lengkap)
- email (email, unique)
- password (password terenkripsi)
- role (peran: admin, kepala_sekolah, wakil_kepala_sekolah, guru, staff)
- jabatan (jabatan dalam organisasi)
- is_active (status aktif/nonaktif)
- remember_token
- created_at
- updated_at

### Tabel Klasifikasi
Menyimpan data klasifikasi surat.
- id (primary key)
- kode (kode klasifikasi, unique)
- nama (nama klasifikasi)
- keterangan (deskripsi klasifikasi)
- created_at
- updated_at

### Tabel Surat Masuk
Menyimpan data surat yang diterima sekolah.
- id (primary key)
- no_agenda (nomor agenda surat masuk)
- no_surat (nomor surat)
- tanggal_surat (tanggal surat dibuat)
- tanggal_terima (tanggal surat diterima)
- asal_surat (pengirim surat)
- perihal (perihal/subjek surat)
- isi_ringkas (ringkasan isi surat)
- klasifikasi_id (foreign key ke tabel klasifikasi)
- file_path (path file surat)
- status (status surat: belum_dibaca, dibaca, diproses, selesai)
- created_by (foreign key ke tabel users)
- updated_by (foreign key ke tabel users)
- created_at
- updated_at

### Tabel Surat Keluar
Menyimpan data surat yang dikeluarkan sekolah.
- id (primary key)
- no_agenda (nomor agenda surat keluar)
- no_surat (nomor surat)
- tanggal_surat (tanggal surat dibuat)
- tanggal_kirim (tanggal surat dikirim)
- tujuan (penerima surat)
- perihal (perihal/subjek surat)
- isi_ringkas (ringkasan isi surat)
- klasifikasi_id (foreign key ke tabel klasifikasi)
- file_path (path file surat)
- status (status surat: draft, menunggu_persetujuan, disetujui, ditolak, dikirim)
- created_by (foreign key ke tabel users)
- updated_by (foreign key ke tabel users)
- created_at
- updated_at

### Tabel Disposisi
Menyimpan data disposisi surat masuk.
- id (primary key)
- surat_masuk_id (foreign key ke tabel surat_masuk)
- dari_id (foreign key ke tabel users, pengirim disposisi)
- kepada_id (foreign key ke tabel users, penerima disposisi)
- tanggal_disposisi (tanggal disposisi dibuat)
- isi_disposisi (isi instruksi disposisi)
- sifat (sifat disposisi: segera, penting, rahasia, biasa)
- catatan (catatan tambahan)
- status (status disposisi: belum_dibaca, dibaca, diproses, selesai)
- created_at
- updated_at

### Tabel Lampiran
Menyimpan data lampiran surat.
- id (primary key)
- surat_masuk_id (foreign key ke tabel surat_masuk, nullable)
- surat_keluar_id (foreign key ke tabel surat_keluar, nullable)
- nama_file (nama file lampiran)
- file_path (path file lampiran)
- tipe_file (tipe/ekstensi file)
- ukuran_file (ukuran file dalam bytes)
- created_at
- updated_at

### Tabel Template Surat
Menyimpan template surat yang dapat digunakan kembali.
- id (primary key)
- nama (nama template)
- jenis (jenis template: surat_masuk, surat_keluar)
- konten (isi template dalam format HTML)
- created_by (foreign key ke tabel users)
- updated_by (foreign key ke tabel users)
- created_at
- updated_at

### Tabel Log Aktivitas
Menyimpan log aktivitas pengguna untuk audit trail.
- id (primary key)
- user_id (foreign key ke tabel users, nullable)
- module (modul yang diakses: auth, surat_masuk, surat_keluar, disposisi, klasifikasi, pengaturan)
- action (aksi yang dilakukan: login, logout, create, update, delete, view)
- description (deskripsi aktivitas)
- ip_address (alamat IP pengguna)
- user_agent (informasi browser pengguna)
- created_at
- updated_at

### Tabel Pengaturan
Menyimpan pengaturan sistem.
- id (primary key)
- key (kunci pengaturan, unique)
- value (nilai pengaturan)
- created_at
- updated_at

## Fitur Sistem

### Manajemen Pengguna
- Registrasi dan login pengguna
- Manajemen profil pengguna
- Manajemen peran dan hak akses
- Aktivasi/deaktivasi akun pengguna

### Dashboard
- Statistik jumlah surat masuk, surat keluar, dan disposisi
- Grafik tren surat masuk dan keluar per bulan
- Daftar surat masuk terbaru
- Daftar surat keluar terbaru
- Daftar disposisi terbaru

### Manajemen Surat Masuk
- Pencatatan surat masuk dengan nomor agenda otomatis
- Upload file surat dan lampiran
- Pencarian dan filter surat masuk
- Disposisi surat masuk
- Pelacakan status surat masuk

### Manajemen Surat Keluar
- Pembuatan surat keluar dengan nomor agenda otomatis
- Upload file surat dan lampiran
- Pencarian dan filter surat keluar
- Persetujuan surat keluar oleh kepala sekolah
- Pelacakan status surat keluar

### Manajemen Disposisi
- Pembuatan disposisi surat masuk
- Notifikasi disposisi baru
- Pelacakan status disposisi
- Penerusan disposisi ke pengguna lain

### Manajemen Klasifikasi
- Pengelolaan klasifikasi surat
- Penggunaan klasifikasi untuk kategorisasi surat

### Pengaturan Sistem
- Konfigurasi informasi sekolah
- Pengaturan format nomor surat dan agenda
- Manajemen pengguna sistem
- Monitoring log aktivitas

## Alur Kerja

### Alur Surat Masuk
1. Staff administrasi menerima surat fisik
2. Staff mencatat surat masuk ke dalam sistem dengan mengisi form dan mengunggah scan surat
3. Sistem memberikan nomor agenda secara otomatis
4. Staff mengirimkan disposisi ke kepala sekolah
5. Kepala sekolah menerima notifikasi disposisi baru
6. Kepala sekolah membaca surat dan memberikan disposisi ke pejabat terkait
7. Pejabat terkait menerima notifikasi disposisi
8. Pejabat terkait memproses disposisi dan menandai sebagai selesai
9. Staff dapat melacak status surat masuk dan disposisi

### Alur Surat Keluar
1. Staff/guru membuat draft surat keluar
2. Staff mengisi form surat keluar dan mengunggah draft surat
3. Sistem memberikan nomor agenda secara otomatis
4. Staff mengajukan persetujuan surat ke kepala sekolah
5. Kepala sekolah menerima notifikasi permohonan persetujuan
6. Kepala sekolah menyetujui atau menolak surat
7. Jika disetujui, staff mencetak surat untuk ditandatangani
8. Staff mengunggah scan surat yang sudah ditandatangani
9. Staff menandai surat sebagai sudah dikirim
10. Staff dapat melacak status surat keluar

## Keamanan

### Autentikasi
- Sistem menggunakan autentikasi berbasis session
- Password dienkripsi menggunakan algoritma bcrypt
- Proteksi terhadap brute force attack dengan rate limiting
- Fitur "remember me" untuk kenyamanan pengguna

### Otorisasi
- Sistem peran (role-based access control) untuk membatasi akses
- Middleware untuk memeriksa hak akses pada setiap route
- Validasi pada sisi server untuk semua input pengguna

### Keamanan Data
- Validasi input untuk mencegah SQL injection
- CSRF protection untuk mencegah cross-site request forgery
- XSS protection untuk mencegah cross-site scripting
- File upload validation untuk mencegah upload file berbahaya

### Audit Trail
- Pencatatan semua aktivitas pengguna
- Log login dan logout
- Log operasi CRUD pada data sensitif
- Pencatatan alamat IP dan user agent

## Teknologi yang Digunakan

### Backend
- PHP 8.1
- Laravel 10.x
- MySQL/MariaDB

### Frontend
- HTML5, CSS3, JavaScript
- Bootstrap 5
- jQuery
- Font Awesome (ikon)
- Chart.js (grafik)

### Tools dan Library
- Composer (manajemen dependensi PHP)
- Laravel Mix (kompilasi asset)
- Laravel Blade (template engine)
- Laravel Eloquent ORM (object-relational mapping)

## Persyaratan Sistem

### Server
- PHP 8.1 atau lebih tinggi
- MySQL 5.7 atau MariaDB 10.3 atau lebih tinggi
- Ekstensi PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- Composer
- Minimal 2GB RAM
- Minimal 10GB ruang disk

### Client
- Browser modern (Chrome, Firefox, Safari, Edge)
- JavaScript diaktifkan
- Resolusi layar minimal 1280x720

## Panduan Instalasi

### Persiapan
1. Pastikan server memenuhi semua persyaratan sistem
2. Siapkan database MySQL/MariaDB
3. Siapkan web server (Apache/Nginx)

### Instalasi
1. Clone repositori dari GitHub atau ekstrak file zip
2. Jalankan `composer install` untuk menginstal dependensi
3. Salin file `.env.example` menjadi `.env`
4. Konfigurasi koneksi database di file `.env`
5. Jalankan `php artisan key:generate` untuk menghasilkan application key
6. Jalankan `php artisan migrate` untuk membuat struktur database
7. Jalankan `php artisan db:seed` untuk mengisi data awal
8. Jalankan `php artisan storage:link` untuk membuat symbolic link ke storage
9. Atur permission folder `storage` dan `bootstrap/cache` agar dapat ditulis oleh web server
10. Akses aplikasi melalui browser

### Konfigurasi Awal
1. Login dengan akun admin default (email: admin@example.com, password: password)
2. Ubah password admin default
3. Konfigurasi pengaturan sekolah di menu Pengaturan
4. Tambahkan pengguna sistem sesuai kebutuhan
5. Tambahkan klasifikasi surat
6. Sistem siap digunakan
