# Petunjuk Deployment Sistem Manajemen Surat Sekolah

## Daftar Isi
1. [Persyaratan Server](#persyaratan-server)
2. [Persiapan Lingkungan](#persiapan-lingkungan)
3. [Deployment ke Shared Hosting](#deployment-ke-shared-hosting)
4. [Deployment ke VPS/Dedicated Server](#deployment-ke-vpsdedicated-server)
5. [Konfigurasi Web Server](#konfigurasi-web-server)
6. [Konfigurasi Database](#konfigurasi-database)
7. [Konfigurasi Aplikasi](#konfigurasi-aplikasi)
8. [Pengaturan Keamanan](#pengaturan-keamanan)
9. [Pemeliharaan](#pemeliharaan)
10. [Troubleshooting](#troubleshooting)

## Persyaratan Server

### Spesifikasi Minimum
- CPU: 1 core
- RAM: 2GB
- Penyimpanan: 10GB SSD
- Bandwidth: 1TB/bulan

### Spesifikasi Rekomendasi
- CPU: 2 core
- RAM: 4GB
- Penyimpanan: 20GB SSD
- Bandwidth: 2TB/bulan

### Software
- Sistem Operasi: Ubuntu 20.04 LTS atau lebih baru
- Web Server: Nginx 1.18+ atau Apache 2.4+
- PHP: 8.1 atau lebih baru
- Database: MySQL 5.7+ atau MariaDB 10.3+
- Composer: 2.0+
- Git: 2.25+

### Ekstensi PHP yang Diperlukan
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD
- Zip
- Curl

## Persiapan Lingkungan

### Instalasi Paket Dasar (Ubuntu)
```bash
# Update repository
sudo apt update
sudo apt upgrade -y

# Instalasi paket dasar
sudo apt install -y curl git unzip

# Instalasi Nginx
sudo apt install -y nginx

# Instalasi PHP dan ekstensi yang diperlukan
sudo apt install -y php8.1-fpm php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath

# Instalasi MariaDB
sudo apt install -y mariadb-server

# Instalasi Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### Konfigurasi PHP
Edit file php.ini untuk mengoptimalkan konfigurasi:
```bash
sudo nano /etc/php/8.1/fpm/php.ini
```

Ubah nilai berikut:
```ini
upload_max_filesize = 10M
post_max_size = 12M
memory_limit = 256M
max_execution_time = 120
```

Restart PHP-FPM:
```bash
sudo systemctl restart php8.1-fpm
```

### Konfigurasi MariaDB
Jalankan script keamanan MariaDB:
```bash
sudo mysql_secure_installation
```

Ikuti petunjuk untuk:
- Mengatur password root
- Menghapus user anonim
- Menonaktifkan login root jarak jauh
- Menghapus database test
- Memuat ulang tabel hak akses

## Deployment ke Shared Hosting

### Persiapan File
1. Pada lingkungan pengembangan, jalankan perintah berikut untuk mengoptimalkan aplikasi:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. Buat arsip dari aplikasi:
   ```bash
   tar -czf sistem-surat.tar.gz .
   ```

### Upload ke Hosting
1. Upload arsip ke hosting menggunakan FTP/SFTP
2. Ekstrak arsip di direktori public_html atau subdirektori yang diinginkan
3. Pastikan file .htaccess ada di direktori root aplikasi

### Konfigurasi Database
1. Buat database baru melalui panel kontrol hosting (cPanel, Plesk, dll)
2. Buat user database dengan hak akses penuh ke database tersebut
3. Import struktur database dari file SQL (jika ada) atau jalankan migrasi

### Konfigurasi Aplikasi
1. Edit file .env dengan informasi database dan pengaturan lainnya
2. Atur permission folder:
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```
3. Buat symbolic link untuk storage:
   ```bash
   php artisan storage:link
   ```

## Deployment ke VPS/Dedicated Server

### Persiapan Server
1. Buat user baru untuk aplikasi:
   ```bash
   sudo adduser surat
   sudo usermod -aG sudo surat
   ```

2. Login sebagai user baru:
   ```bash
   su - surat
   ```

### Clone Repositori
1. Buat direktori untuk aplikasi:
   ```bash
   mkdir -p /var/www/sistem-surat
   ```

2. Clone repositori:
   ```bash
   git clone https://github.com/username/sistem-surat.git /var/www/sistem-surat
   ```

3. Atur permission:
   ```bash
   sudo chown -R surat:www-data /var/www/sistem-surat
   sudo chmod -R 755 /var/www/sistem-surat
   sudo chmod -R 775 /var/www/sistem-surat/storage /var/www/sistem-surat/bootstrap/cache
   ```

### Instalasi Dependensi
1. Masuk ke direktori aplikasi:
   ```bash
   cd /var/www/sistem-surat
   ```

2. Instal dependensi:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

### Konfigurasi Aplikasi
1. Salin file .env.example:
   ```bash
   cp .env.example .env
   ```

2. Edit file .env:
   ```bash
   nano .env
   ```

3. Atur nilai-nilai berikut:
   ```
   APP_NAME="Sistem Manajemen Surat Sekolah"
   APP_ENV=production
   APP_KEY=
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=surat_sekolah
   DB_USERNAME=surat_user
   DB_PASSWORD=your_secure_password
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Buat symbolic link untuk storage:
   ```bash
   php artisan storage:link
   ```

### Konfigurasi Database
1. Buat database dan user:
   ```bash
   sudo mysql -u root -p
   ```

2. Di prompt MySQL, jalankan:
   ```sql
   CREATE DATABASE surat_sekolah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'surat_user'@'localhost' IDENTIFIED BY 'your_secure_password';
   GRANT ALL PRIVILEGES ON surat_sekolah.* TO 'surat_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

3. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate --seed
   ```

### Optimasi Aplikasi
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Konfigurasi Web Server

### Nginx
Buat file konfigurasi baru:
```bash
sudo nano /etc/nginx/sites-available/sistem-surat
```

Isi dengan konfigurasi berikut:
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/sistem-surat/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan konfigurasi:
```bash
sudo ln -s /etc/nginx/sites-available/sistem-surat /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Apache
Buat file konfigurasi baru:
```bash
sudo nano /etc/apache2/sites-available/sistem-surat.conf
```

Isi dengan konfigurasi berikut:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/sistem-surat/public

    <Directory /var/www/sistem-surat/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/sistem-surat-error.log
    CustomLog ${APACHE_LOG_DIR}/sistem-surat-access.log combined
</VirtualHost>
```

Aktifkan konfigurasi:
```bash
sudo a2ensite sistem-surat.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## Konfigurasi HTTPS dengan Let's Encrypt

### Instalasi Certbot
```bash
sudo apt install -y certbot

# Untuk Nginx
sudo apt install -y python3-certbot-nginx

# Untuk Apache
sudo apt install -y python3-certbot-apache
```

### Mendapatkan Sertifikat
```bash
# Untuk Nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Untuk Apache
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

### Pembaruan Otomatis
Certbot secara otomatis menambahkan cron job untuk pembaruan sertifikat. Anda dapat memeriksa dengan:
```bash
sudo systemctl status certbot.timer
```

## Pengaturan Keamanan

### Firewall
Aktifkan dan konfigurasi UFW:
```bash
sudo ufw allow ssh
sudo ufw allow http
sudo ufw allow https
sudo ufw enable
```

### Fail2Ban
Instal dan konfigurasi Fail2Ban untuk melindungi dari serangan brute force:
```bash
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### File Permissions
Pastikan permission file sudah benar:
```bash
sudo find /var/www/sistem-surat -type f -exec chmod 644 {} \;
sudo find /var/www/sistem-surat -type d -exec chmod 755 {} \;
sudo chown -R surat:www-data /var/www/sistem-surat
sudo chmod -R 775 /var/www/sistem-surat/storage /var/www/sistem-surat/bootstrap/cache
```

## Pemeliharaan

### Backup Reguler
Buat script backup untuk database dan file aplikasi:
```bash
#!/bin/bash
# backup.sh

# Variabel
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/path/to/backups"
APP_DIR="/var/www/sistem-surat"
DB_USER="surat_user"
DB_PASS="your_secure_password"
DB_NAME="surat_sekolah"

# Buat direktori backup jika belum ada
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_backup_$TIMESTAMP.sql.gz

# Backup file aplikasi
tar -czf $BACKUP_DIR/app_backup_$TIMESTAMP.tar.gz $APP_DIR

# Hapus backup yang lebih dari 30 hari
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +30 -delete
find $BACKUP_DIR -name "app_backup_*.tar.gz" -mtime +30 -delete
```

Jadwalkan dengan cron:
```bash
sudo crontab -e
```

Tambahkan baris berikut untuk backup harian pada jam 2 pagi:
```
0 2 * * * /path/to/backup.sh
```

### Update Aplikasi
Untuk memperbarui aplikasi dari repositori:
```bash
cd /var/www/sistem-surat
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Monitoring
Instal dan konfigurasi alat monitoring seperti Monit:
```bash
sudo apt install -y monit
```

Konfigurasi Monit untuk memantau layanan penting:
```bash
sudo nano /etc/monit/conf.d/sistem-surat
```

Isi dengan:
```
check host yourdomain.com with address yourdomain.com
    if failed port 80 protocol http for 3 cycles then alert
    if failed port 443 protocol https for 3 cycles then alert

check process nginx with pidfile /var/run/nginx.pid
    start program = "/etc/init.d/nginx start"
    stop program = "/etc/init.d/nginx stop"
    if failed host 127.0.0.1 port 80 then restart
    if 5 restarts within 5 cycles then timeout

check process php8.1-fpm with pidfile /var/run/php/php8.1-fpm.pid
    start program = "/etc/init.d/php8.1-fpm start"
    stop program = "/etc/init.d/php8.1-fpm stop"
    if failed unixsocket /var/run/php/php8.1-fpm.sock then restart
    if 5 restarts within 5 cycles then timeout

check process mysql with pidfile /var/run/mysqld/mysqld.pid
    start program = "/etc/init.d/mysql start"
    stop program = "/etc/init.d/mysql stop"
    if failed host 127.0.0.1 port 3306 then restart
    if 5 restarts within 5 cycles then timeout
```

Restart Monit:
```bash
sudo systemctl restart monit
```

## Troubleshooting

### Log Files
Lokasi file log penting:
- Log aplikasi Laravel: `/var/www/sistem-surat/storage/logs/laravel.log`
- Log Nginx: `/var/log/nginx/error.log` dan `/var/log/nginx/access.log`
- Log Apache: `/var/log/apache2/error.log` dan `/var/log/apache2/access.log`
- Log PHP: `/var/log/php8.1-fpm.log`
- Log MySQL/MariaDB: `/var/log/mysql/error.log`

### Masalah Umum dan Solusi

#### 500 Internal Server Error
1. Periksa log web server dan aplikasi
2. Pastikan permission file dan folder sudah benar
3. Periksa file .env untuk konfigurasi yang salah
4. Coba hapus file cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

#### 404 Not Found untuk Semua Halaman
1. Pastikan mod_rewrite (Apache) atau try_files (Nginx) sudah dikonfigurasi dengan benar
2. Periksa DocumentRoot/root di konfigurasi web server
3. Pastikan file .htaccess (Apache) ada dan benar

#### Database Connection Error
1. Periksa kredensial database di file .env
2. Pastikan layanan database berjalan: `sudo systemctl status mysql`
3. Periksa apakah user database memiliki hak akses yang cukup
4. Coba koneksi manual ke database: `mysql -u surat_user -p surat_sekolah`

#### Permission Denied
1. Periksa ownership dan permission folder storage dan bootstrap/cache:
   ```bash
   sudo chown -R surat:www-data /var/www/sistem-surat/storage /var/www/sistem-surat/bootstrap/cache
   sudo chmod -R 775 /var/www/sistem-surat/storage /var/www/sistem-surat/bootstrap/cache
   ```

#### Upload File Gagal
1. Periksa nilai upload_max_filesize dan post_max_size di php.ini
2. Pastikan folder storage/app/public memiliki permission yang benar
3. Periksa apakah symbolic link storage sudah dibuat: `php artisan storage:link`

## Kesimpulan

Petunjuk deployment ini mencakup langkah-langkah untuk menerapkan Sistem Manajemen Surat Sekolah ke lingkungan produksi, baik di shared hosting maupun VPS/dedicated server. Pastikan untuk mengikuti praktik keamanan terbaik dan melakukan backup reguler untuk melindungi data penting.

Untuk bantuan lebih lanjut, silakan hubungi tim pengembang atau konsultasikan dengan administrator sistem Anda.
