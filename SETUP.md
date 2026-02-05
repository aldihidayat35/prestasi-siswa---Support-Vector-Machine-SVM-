# 📘 Panduan Setup Lengkap

Dokumen ini berisi langkah-langkah detail untuk menginstall dan menjalankan aplikasi **Sistem Prediksi Prestasi Akademik Siswa dengan SVM** pada device baru.

---

## 📋 Daftar Isi

1. [Persiapan Environment](#1-persiapan-environment)
2. [Download Source Code](#2-download-source-code)
3. [Install Dependencies](#3-install-dependencies)
4. [Konfigurasi Environment](#4-konfigurasi-environment)
5. [Setup Database](#5-setup-database)
6. [Training Model SVM](#6-training-model-svm)
7. [Menjalankan Aplikasi](#7-menjalankan-aplikasi)
8. [Verifikasi Instalasi](#8-verifikasi-instalasi)
9. [Troubleshooting](#9-troubleshooting)

---

## 1. Persiapan Environment

### 1.1 Install Laragon (Windows - Recommended)

1. Download Laragon dari https://laragon.org/download/
2. Pilih versi **Laragon Full** (sudah termasuk PHP 8.x, MySQL, dll)
3. Install dengan konfigurasi default
4. Jalankan Laragon dan klik **Start All**

### 1.2 Verifikasi Instalasi

Buka terminal (command prompt atau Git Bash) dan jalankan:

```bash
# Cek versi PHP
php -v
# Output: PHP 8.2.x atau lebih tinggi

# Cek Composer
composer -V
# Output: Composer version 2.x.x

# Cek MySQL
mysql --version
# Output: mysql Ver 8.x.x

# Cek Git
git --version
# Output: git version 2.x.x
```

### 1.3 PHP Extensions

Pastikan extensions berikut aktif (biasanya sudah aktif di Laragon):

```
bcmath, ctype, curl, dom, fileinfo, json, mbstring, 
openssl, pdo, pdo_mysql, tokenizer, xml
```

Untuk mengecek:
```bash
php -m
```

---

## 2. Download Source Code

### 2.1 Clone dari GitHub

```bash
# Pindah ke folder www Laragon
cd C:\laragon\www

# Clone repository
git clone https://github.com/aldihidayat35/prestasi-siswa---Support-Vector-Machine-SVM-.git
# Masuk ke folder project
cd prestasi-siswa-svm
```

### 2.2 Download Manual (Alternatif)

1. Download ZIP dari GitHub
2. Extract ke `C:\laragon\www\`
3. Rename folder menjadi `prestasi-siswa-svm`

---

## 3. Install Dependencies

### 3.1 PHP Dependencies (Wajib)

```bash
# Di dalam folder project
composer install
```

Tunggu hingga proses selesai. Output yang diharapkan:
```
Installing dependencies from lock file
...
Generating optimized autoload files
> @php artisan package:discover --ansi
Package manifest generated successfully.
```

### 3.2 JavaScript Dependencies (Opsional)

```bash
# Jika file package.json ada
npm install
npm run build
```

---

## 4. Konfigurasi Environment

### 4.1 Copy File .env

```bash
# Windows Command Prompt
copy .env.example .env

# Windows PowerShell atau Linux/Mac
cp .env.example .env
```

### 4.2 Generate Application Key

```bash
php artisan key:generate
```

Output:
```
INFO  Application key set successfully.
```

### 4.3 Edit File .env

Buka file `.env` dengan text editor (Notepad++, VS Code, dll) dan ubah:

```env
# Nama Aplikasi
APP_NAME="Prestasi Siswa SVM"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Konfigurasi Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prestasi_siswa_svm
DB_USERNAME=root
DB_PASSWORD=
```

> **Note:** Jika MySQL Anda memiliki password, isi `DB_PASSWORD=password_anda`

---

## 5. Setup Database

### 5.1 Buat Database Baru

**Opsi A: Via phpMyAdmin (Visual)**

1. Buka browser: http://localhost/phpmyadmin
2. Klik **"New"** di sidebar kiri
3. Database name: `prestasi_siswa_svm`
4. Collation: `utf8mb4_unicode_ci`
5. Klik **"Create"**

**Opsi B: Via MySQL CLI**

```bash
mysql -u root

# Di dalam MySQL prompt
CREATE DATABASE prestasi_siswa_svm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 5.2 Jalankan Migration

```bash
php artisan migrate
```

Output yang diharapkan:
```
INFO  Preparing database.
Creating migration table ........................... 12ms DONE

INFO  Running migrations.
2026_02_05_000001_create_roles_table ............... 15ms DONE
2026_02_05_000002_create_users_table ............... 8ms DONE
2026_02_05_000003_create_students_table ............ 5ms DONE
...
```

### 5.3 Jalankan Seeder (Data Awal)

```bash
php artisan db:seed
```

Output:
```
INFO  Seeding database.
Database\Seeders\RoleSeeder ........................ RUNNING
Database\Seeders\RoleSeeder ........................ DONE
Database\Seeders\UserSeeder ........................ RUNNING
Database\Seeders\UserSeeder ........................ DONE
...
```

### 5.4 Alternatif: Reset Database + Seed Sekaligus

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan:
- Drop semua tabel
- Jalankan semua migration
- Jalankan semua seeder

---

## 6. Training Model SVM

### 6.1 Jalankan Training

```bash
php artisan ml:train --activate
```

### 6.2 Output yang Diharapkan

```
========================================
    SVM MODEL TRAINING
========================================
Loading training data...
Found 40 samples for training

Training SVM model...
[========================================] 100%

Model trained successfully!

Testing model accuracy...
Model Accuracy: 37.50%

Saving model...
Model saved and activated!
========================================
```

### 6.3 Troubleshooting Training

Jika muncul error "No training data found":
1. Pastikan seeder sudah dijalankan: `php artisan db:seed`
2. Cek apakah data ada di tabel `ml_datasets`

---

## 7. Menjalankan Aplikasi

### 7.1 Menggunakan Artisan Serve

```bash
php artisan serve
```

Output:
```
INFO  Server running on [http://127.0.0.1:8000].
Press Ctrl+C to stop the server
```

Buka browser dan akses: **http://localhost:8000**

### 7.2 Menggunakan Laragon (Alternatif)

1. Pastikan Laragon sudah di-Start
2. Folder project ada di `C:\laragon\www\`
3. Akses via:
   - http://prestasi-siswa-svm.test (jika auto vhost aktif)
   - http://localhost/prestasi-siswa-svm/public

---

## 8. Verifikasi Instalasi

### 8.1 Login Test

1. Buka http://localhost:8000
2. Login dengan akun admin:
   - Email: `admin@sman2bukittinggi.sch.id`
   - Password: `password`
3. Pastikan dashboard muncul

### 8.2 Cek Fitur

- [ ] Dashboard menampilkan statistik
- [ ] Menu Siswa bisa diakses
- [ ] Menu Aktivitas Belajar berfungsi
- [ ] Menu Prediksi bisa melakukan prediksi
- [ ] Menu Model ML menampilkan model aktif

### 8.3 Test Prediksi

1. Buka menu **Prediksi** > **Buat Prediksi**
2. Pilih siswa
3. Masukkan data aktivitas belajar
4. Klik **Prediksi**
5. Pastikan hasil prediksi muncul

---

## 9. Troubleshooting

### Error: "Could not find driver"

**Penyebab:** Extension PDO MySQL tidak aktif

**Solusi:**
1. Buka `php.ini` (biasanya di `C:\laragon\bin\php\php-8.x.x\php.ini`)
2. Cari baris `;extension=pdo_mysql`
3. Hapus tanda `;` di awal baris
4. Simpan dan restart Laragon

### Error: "SQLSTATE[HY000] Access denied"

**Penyebab:** Password database salah

**Solusi:**
1. Buka `.env`
2. Sesuaikan `DB_PASSWORD` dengan password MySQL Anda
3. Jalankan `php artisan config:clear`

### Error: "Permission denied" pada storage

**Solusi Windows:**
```bash
icacls storage /grant Everyone:F /T
icacls bootstrap\cache /grant Everyone:F /T
```

### Error: "Class not found"

**Solusi:**
```bash
composer dump-autoload
php artisan optimize:clear
```

### Halaman Blank / Error 500

**Solusi:**
1. Edit `.env`, set `APP_DEBUG=true`
2. Cek file `storage/logs/laravel.log`
3. Jalankan:
```bash
php artisan optimize:clear
php artisan config:clear
```

### Error: "No active model found"

**Solusi:**
```bash
php artisan ml:train --activate
```

---

## 📞 Quick Commands Reference

```bash
# Start server
php artisan serve

# Reset database lengkap
php artisan migrate:fresh --seed

# Train model ML
php artisan ml:train --activate

# Clear semua cache
php artisan optimize:clear

# Regenerate autoload
composer dump-autoload
```

---

## ✅ Checklist Setup Lengkap

- [ ] Laragon terinstall dan berjalan
- [ ] PHP 8.2+ tersedia
- [ ] Composer terinstall
- [ ] MySQL/MariaDB berjalan
- [ ] Git terinstall
- [ ] Repository di-clone
- [ ] `composer install` berhasil
- [ ] File `.env` sudah dikonfigurasi
- [ ] Database `prestasi_siswa_svm` dibuat
- [ ] `php artisan migrate` berhasil
- [ ] `php artisan db:seed` berhasil
- [ ] `php artisan ml:train --activate` berhasil
- [ ] Bisa login ke aplikasi
- [ ] Dashboard menampilkan data
- [ ] Fitur prediksi berfungsi

---

<p align="center">
  <b>Setup Selesai! 🎉</b><br>
  Aplikasi siap digunakan.
</p>
