# Sistem Prediksi Prestasi Akademik Siswa dengan SVM

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-purple?style=for-the-badge&logo=bootstrap" alt="Bootstrap">
</p>

Aplikasi web untuk memprediksi prestasi akademik siswa berdasarkan aktivitas belajar menggunakan algoritma **Support Vector Machine (SVM)**. Dikembangkan sebagai tugas akhir/skripsi untuk SMA Negeri 2 Bukittinggi.

---

## 📋 Daftar Isi

- [Fitur Aplikasi](#-fitur-aplikasi)
- [System Requirements](#-system-requirements)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Akun Default](#-akun-default)
- [Struktur Folder](#-struktur-folder)
- [Penggunaan](#-penggunaan)
- [Troubleshooting](#-troubleshooting)
- [Teknologi](#-teknologi)

---

## ✨ Fitur Aplikasi

### Admin
- 📊 Dashboard dengan statistik dan visualisasi
- 👥 Manajemen pengguna (Admin & Guru)
- 🎓 Manajemen data siswa
- 📝 Input aktivitas belajar siswa
- 📈 Manajemen nilai akademik
- 🤖 Training model SVM
- 🔮 Prediksi prestasi siswa (individual & batch)
- 📋 Manajemen dataset (generate, split, clear)
- 📖 Tutorial dan dokumentasi

### Guru
- 📊 Dashboard personal
- 📝 Input aktivitas belajar siswa
- 🔮 Lihat hasil prediksi

### Machine Learning
- ✅ Implementasi SVM native PHP (tanpa Python)
- ✅ Algoritma SMO (Sequential Minimal Optimization)
- ✅ Multi-class classification (One-vs-One)
- ✅ RBF Kernel
- ✅ Evaluasi akurasi model

---

## 💻 System Requirements

### Software yang Diperlukan

| Software | Versi Minimum | Keterangan |
|----------|---------------|------------|
| PHP | 8.2+ | Dengan extensions yang diperlukan |
| Composer | 2.x | Dependency manager PHP |
| MySQL/MariaDB | 8.0 / 10.4 | Database server |
| Git | 2.x | Version control |
| Node.js | 18.x | Opsional, untuk build assets |

### PHP Extensions

Pastikan extensions berikut aktif di `php.ini`:

```
bcmath, ctype, curl, dom, fileinfo, json, mbstring, 
openssl, pdo, pdo_mysql, tokenizer, xml
```

### Rekomendasi Development Environment

- **Windows**: [Laragon](https://laragon.org/) (Recommended) atau XAMPP
- **macOS**: Laravel Valet atau MAMP
- **Linux**: Native LAMP stack

---

## 🚀 Instalasi

### Langkah 1: Clone Repository

```bash
# Clone repository
git clone https://github.com/[username]/prestasi-siswa-svm.git

# Masuk ke folder project
cd prestasi-siswa-svm
```

### Langkah 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies (opsional)
npm install
npm run build
```

### Langkah 3: Setup Environment

```bash
# Copy file environment
copy .env.example .env        # Windows
cp .env.example .env          # Linux/Mac

# Generate application key
php artisan key:generate
```

### Langkah 4: Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prestasi_siswa_svm
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 5: Buat Database

**Via phpMyAdmin:**
1. Buka http://localhost/phpmyadmin
2. Klik "New" di sidebar
3. Masukkan nama: `prestasi_siswa_svm`
4. Pilih collation: `utf8mb4_unicode_ci`
5. Klik "Create"

**Via MySQL CLI:**
```bash
mysql -u root -p
CREATE DATABASE prestasi_siswa_svm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### Langkah 6: Jalankan Migration & Seeder

```bash
# Jalankan migration (buat tabel)
php artisan migrate

# Jalankan seeder (isi data awal)
php artisan db:seed

# Atau keduanya sekaligus
php artisan migrate:fresh --seed
```

### Langkah 7: Training Model SVM

```bash
# Train dan aktifkan model SVM
php artisan ml:train --activate
```

Output yang diharapkan:
```
========================================
    SVM MODEL TRAINING
========================================
Loading training data...
Found 40 samples for training
Training SVM model...
Model trained successfully!
Testing model accuracy...
Model Accuracy: XX.XX%
Model saved and activated!
```

### Langkah 8: Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi di: **http://localhost:8000**

---

## ⚙️ Konfigurasi

### File .env Lengkap

```env
APP_NAME="Prestasi Siswa SVM"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prestasi_siswa_svm
DB_USERNAME=root
DB_PASSWORD=

# Session & Cache
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

### Konfigurasi SVM

Parameter default:
- **Kernel**: RBF (Radial Basis Function)
- **C (Regularization)**: 1.0
- **Gamma**: 0.1
- **Tolerance**: 0.001
- **Max Iterations**: 1000

---

## 🏃 Menjalankan Aplikasi

### Development Server

```bash
php artisan serve
```

### Dengan Laragon

1. Letakkan folder project di `C:\laragon\www\`
2. Start Laragon
3. Akses via:
   - http://prestasi-siswa-svm.test
   - http://localhost/prestasi-siswa-svm/public

### Production

```bash
# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 🔐 Akun Default

### Administrator
| Field | Value |
|-------|-------|
| Email | `admin@sman2bukittinggi.sch.id` |
| Password | `password` |
| Role | Admin |

### Guru
| Field | Value |
|-------|-------|
| Email | `ahmad.syafii@sman2bukittinggi.sch.id` |
| Password | `password` |
| Role | Guru |

> ⚠️ **PENTING**: Segera ganti password default setelah login di production!

---

## 📁 Struktur Folder

```
prestasi-siswa-svm/
├── app/
│   ├── Console/Commands/      # Artisan commands (ml:train)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Controller untuk admin
│   │   │   ├── Guru/          # Controller untuk guru
│   │   │   └── Auth/          # Authentication controllers
│   │   └── Middleware/        # Custom middleware
│   ├── Models/                # Eloquent models
│   └── Services/
│       └── SVM/               # Implementasi SVM PHP native
│           ├── Kernel.php     # Kernel functions (RBF, Linear, Polynomial)
│           └── SVM.php        # SVM classifier dengan SMO
├── config/                    # Configuration files
├── database/
│   ├── migrations/            # Database schema
│   └── seeders/               # Sample data seeders
├── public/                    # Web root
├── resources/
│   └── views/
│       ├── admin/             # Views untuk admin
│       ├── guru/              # Views untuk guru
│       ├── auth/              # Login views
│       └── layouts/           # Layout templates
├── routes/
│   └── web.php                # Route definitions
├── storage/
│   ├── app/ml-models/         # Trained SVM models (.json)
│   └── logs/                  # Application logs
├── .env                       # Environment config
├── composer.json              # PHP dependencies
└── README.md                  # Dokumentasi ini
```

---

## 📖 Penggunaan

### 1. Login

1. Buka http://localhost:8000
2. Masukkan email dan password
3. Sistem akan redirect ke dashboard sesuai role

### 2. Generate Dataset

1. Buka menu **Dataset**
2. Klik **Generate Sekarang**
3. Dataset akan dibuat dari data aktivitas belajar dan nilai akademik

### 3. Training Model

**Via Web Interface:**
1. Buka menu **Model ML**
2. Klik **Training Baru**
3. Atur parameter (opsional)
4. Klik **Mulai Training**

**Via Command Line:**
```bash
php artisan ml:train --activate
```

### 4. Melakukan Prediksi

**Prediksi Individual:**
1. Buka menu **Prediksi**
2. Pilih siswa
3. Masukkan data aktivitas belajar
4. Klik **Prediksi**

**Prediksi Batch:**
1. Buka menu **Prediksi** > **Prediksi Batch**
2. Pilih periode
3. Sistem akan memprediksi semua siswa

### 5. Melihat Hasil

- Dashboard menampilkan statistik prediksi
- Menu **Prediksi** menampilkan riwayat prediksi
- Export hasil ke CSV/Excel

---

## 🔧 Troubleshooting

### Error: "Could not find driver"

**Penyebab:** Extension PDO MySQL tidak aktif.

**Solusi:**
1. Buka `php.ini`
2. Cari `;extension=pdo_mysql`
3. Hapus tanda `;` di awal baris
4. Restart web server

### Error: "Permission denied" pada storage

**Solusi Windows:**
```bash
icacls storage /grant Everyone:F /T
icacls bootstrap/cache /grant Everyone:F /T
```

**Solusi Linux/Mac:**
```bash
chmod -R 775 storage bootstrap/cache
```

### Error: "Class not found"

**Solusi:**
```bash
composer dump-autoload
php artisan optimize:clear
```

### Error: "SQLSTATE[HY000] Access denied"

**Penyebab:** Kredensial database salah.

**Solusi:**
1. Periksa `DB_USERNAME` dan `DB_PASSWORD` di `.env`
2. Jalankan `php artisan config:clear`

### Halaman Blank / Error 500

**Solusi:**
1. Set `APP_DEBUG=true` di `.env`
2. Cek file `storage/logs/laravel.log`
3. Jalankan `php artisan optimize:clear`

### Model tidak aktif / "Tidak ada model aktif"

**Solusi:**
```bash
php artisan ml:train --activate
```

---

## 📜 Artisan Commands

| Command | Deskripsi |
|---------|-----------|
| `php artisan serve` | Jalankan development server |
| `php artisan migrate` | Jalankan database migrations |
| `php artisan migrate:fresh --seed` | Reset database + seed |
| `php artisan db:seed` | Jalankan database seeders |
| `php artisan ml:train --activate` | Train dan aktifkan model SVM |
| `php artisan cache:clear` | Hapus cache aplikasi |
| `php artisan config:clear` | Hapus cache konfigurasi |
| `php artisan route:clear` | Hapus cache routes |
| `php artisan view:clear` | Hapus cache views |
| `php artisan optimize:clear` | Hapus semua cache |
| `composer dump-autoload` | Regenerate autoload files |

---

## 🔬 Teknologi

### Backend
- **Laravel 12.x** - PHP Framework
- **PHP 8.2+** - Server-side language
- **MySQL 8.0** - Database

### Frontend
- **Bootstrap 5.3** - CSS Framework
- **Bootstrap Icons** - Icon library
- **Chart.js** - Grafik dan visualisasi

### Machine Learning
- **Native PHP SVM** - Implementasi SVM tanpa dependency
- **SMO Algorithm** - Sequential Minimal Optimization
- **RBF Kernel** - Radial Basis Function

### Variabel Machine Learning

**Features (Input):**

| No | Feature | Deskripsi | Range |
|----|---------|-----------|-------|
| 1 | Attendance Rate | Tingkat kehadiran | 0-100% |
| 2 | Study Duration | Durasi belajar per hari | 0-24 jam |
| 3 | Task Frequency | Frekuensi pengerjaan tugas | 0-100 |
| 4 | Discussion Participation | Partisipasi diskusi | 0-100% |
| 5 | Media Usage | Penggunaan media pembelajaran | 0-100% |
| 6 | Discipline Score | Skor kedisiplinan | 0-100 |

**Label (Output):**

| Label | Kriteria |
|-------|----------|
| Rendah | Nilai rata-rata < 60 |
| Sedang | Nilai rata-rata 60-79 |
| Tinggi | Nilai rata-rata ≥ 80 |

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

<p align="center">
  Dikembangkan dengan ❤️ untuk SMA Negeri 2 Bukittinggi
</p>
