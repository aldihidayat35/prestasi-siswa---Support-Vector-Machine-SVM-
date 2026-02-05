@extends('layouts.app')

@section('title', 'Panduan Setup Aplikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="bi bi-gear-wide-connected me-2"></i>Panduan Setup Aplikasi</h4>
        <p class="text-muted mb-0">Langkah-langkah instalasi pada device baru</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

{{-- System Requirements --}}
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-cpu me-2"></i>1. System Requirements</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">Software yang Dibutuhkan:</h6>
                <table class="table table-sm">
                    <tr>
                        <td><i class="bi bi-check-circle text-success me-2"></i>PHP</td>
                        <td><code>>= 8.2</code></td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-check-circle text-success me-2"></i>Composer</td>
                        <td><code>>= 2.x</code></td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-check-circle text-success me-2"></i>MySQL / MariaDB</td>
                        <td><code>>= 8.0 / 10.4</code></td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-check-circle text-success me-2"></i>Node.js (opsional)</td>
                        <td><code>>= 18.x</code></td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-check-circle text-success me-2"></i>Git</td>
                        <td><code>>= 2.x</code></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold mb-3">PHP Extensions yang Diperlukan:</h6>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-secondary">BCMath</span>
                    <span class="badge bg-secondary">Ctype</span>
                    <span class="badge bg-secondary">Fileinfo</span>
                    <span class="badge bg-secondary">JSON</span>
                    <span class="badge bg-secondary">Mbstring</span>
                    <span class="badge bg-secondary">OpenSSL</span>
                    <span class="badge bg-secondary">PDO</span>
                    <span class="badge bg-secondary">PDO_MySQL</span>
                    <span class="badge bg-secondary">Tokenizer</span>
                    <span class="badge bg-secondary">XML</span>
                    <span class="badge bg-secondary">cURL</span>
                </div>

                <div class="alert alert-info mt-3 small mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    <strong>Rekomendasi:</strong> Gunakan <a href="https://laragon.org/" target="_blank">Laragon</a> (Windows)
                    atau <a href="https://www.apachefriends.org/" target="_blank">XAMPP</a> yang sudah include semua kebutuhan.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Step 1: Clone Repository --}}
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-git me-2"></i>2. Clone Repository</h5>
    </div>
    <div class="card-body">
        <p>Clone project dari GitHub ke folder web server:</p>

        <div class="bg-dark text-light p-3 rounded mb-3">
            <code class="text-info"># Untuk Laragon (C:\laragon\www)</code><br>
            <code>cd C:\laragon\www</code><br><br>
            <code class="text-info"># Clone repository</code><br>
            <code>git clone https://github.com/[username]/prestasi-siswa-svm.git</code><br><br>
            <code class="text-info"># Masuk ke folder project</code><br>
            <code>cd prestasi-siswa-svm</code>
        </div>

        <div class="alert alert-warning small">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Ganti <code>[username]</code> dengan username GitHub yang sesuai.
        </div>
    </div>
</div>

{{-- Step 2: Install Dependencies --}}
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>3. Install Dependencies</h5>
    </div>
    <div class="card-body">
        <h6 class="fw-bold">3.1 Install PHP Dependencies (Composer)</h6>
        <div class="bg-dark text-light p-3 rounded mb-3">
            <code class="text-info"># Install semua package PHP</code><br>
            <code>composer install</code>
        </div>

        <h6 class="fw-bold">3.2 Install JavaScript Dependencies (Opsional)</h6>
        <div class="bg-dark text-light p-3 rounded mb-3">
            <code class="text-info"># Jika menggunakan npm</code><br>
            <code>npm install</code><br><br>
            <code class="text-info"># Build assets</code><br>
            <code>npm run build</code>
        </div>

        <div class="alert alert-info small">
            <i class="bi bi-info-circle me-2"></i>
            Jika tidak ada file <code>package.json</code>, langkah npm bisa dilewati.
        </div>
    </div>
</div>

{{-- Step 3: Environment Configuration --}}
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-sliders me-2"></i>4. Konfigurasi Environment</h5>
    </div>
    <div class="card-body">
        <h6 class="fw-bold">4.1 Copy file .env</h6>
        <div class="bg-dark text-light p-3 rounded mb-3">
            <code class="text-info"># Copy template environment</code><br>
            <code>copy .env.example .env</code><br><br>
            <code class="text-info"># Atau di Linux/Mac</code><br>
            <code>cp .env.example .env</code>
        </div>

        <h6 class="fw-bold">4.2 Generate Application Key</h6>
        <div class="bg-dark text-light p-3 rounded mb-3">
            <code>php artisan key:generate</code>
        </div>

        <h6 class="fw-bold">4.3 Edit file .env</h6>
        <p>Buka file <code>.env</code> dan sesuaikan konfigurasi berikut:</p>

        <div class="bg-light border rounded p-3">
<pre class="mb-0"><code class="text-dark">APP_NAME="Prestasi Siswa SVM"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prestasi_siswa_svm
DB_USERNAME=root
DB_PASSWORD=

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file</code></pre>
        </div>

        <div class="alert alert-warning mt-3 small">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Penting:</strong> Sesuaikan <code>DB_PASSWORD</code> jika database MySQL Anda menggunakan password.
        </div>
    </div>
</div>

{{-- Step 4: Database Setup --}}
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-database me-2"></i>5. Setup Database</h5>
    </div>
    <div class="card-body">
        <h6 class="fw-bold">5.1 Buat Database</h6>
        <p>Buat database baru melalui phpMyAdmin atau command line:</p>

        <div class="bg-dark text-light p-3 rounded mb-3">
            <code class="text-info"># Via MySQL CLI</code><br>
            <code>mysql -u root -p</code><br>
            <code>CREATE DATABASE prestasi_siswa_svm;</code><br>
            <code>EXIT;</code>
        </div>

        <div class="text-center my-3">
            <span class="text-muted">— atau melalui phpMyAdmin —</span>
        </div>

        <ol class="small">
            <li>Buka <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a></li>
            <li>Klik "New" di sidebar kiri</li>
            <li>Masukkan nama database: <code>prestasi_siswa_svm</code></li>
            <li>Pilih collation: <code>utf8mb4_unicode_ci</code></li>
            <li>Klik "Create"</li>
        </ol>

        <h6 class="fw-bold mt-4">5.2 Jalankan Migration</h6>
        <div class="bg-dark text-light p-3 rounded mb-3">
            <code class="text-info"># Buat semua tabel database</code><br>
            <code>php artisan migrate</code>
        </div>

        <h6 class="fw-bold">5.3 Jalankan Seeder (Data Awal)</h6>
        <div class="bg-dark text-light p-3 rounded mb-3">
            <code class="text-info"># Isi data awal (users, roles, students, dll)</code><br>
            <code>php artisan db:seed</code>
        </div>

        <div class="alert alert-success small">
            <i class="bi bi-check-circle me-2"></i>
            Atau jalankan keduanya sekaligus: <code>php artisan migrate:fresh --seed</code>
        </div>
    </div>
</div>

{{-- Step 5: Train ML Model --}}
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-robot me-2"></i>6. Training Model SVM</h5>
    </div>
    <div class="card-body">
        <p>Setelah data seeder dijalankan, latih model SVM untuk prediksi:</p>

        <div class="bg-dark text-light p-3 rounded mb-3">
            <code class="text-info"># Train dan aktifkan model SVM</code><br>
            <code>php artisan ml:train --activate</code>
        </div>

        <p>Output yang diharapkan:</p>
        <div class="bg-light border rounded p-3">
<pre class="mb-0 small"><code>========================================
    SVM MODEL TRAINING
========================================
Loading training data...
Found 40 samples for training
Training SVM model...
Model trained successfully!
Testing model accuracy...
Model Accuracy: 37.50%
Model saved and activated!</code></pre>
        </div>

        <div class="alert alert-info small mt-3">
            <i class="bi bi-lightbulb me-2"></i>
            Akurasi akan meningkat seiring penambahan data training yang lebih banyak dan berkualitas.
        </div>
    </div>
</div>

{{-- Step 6: Run Application --}}
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-play-circle me-2"></i>7. Jalankan Aplikasi</h5>
    </div>
    <div class="card-body">
        <h6 class="fw-bold">7.1 Menggunakan Artisan Serve</h6>
        <div class="bg-dark text-light p-3 rounded mb-3">
            <code class="text-info"># Jalankan development server</code><br>
            <code>php artisan serve</code>
        </div>

        <p>Aplikasi akan berjalan di: <a href="http://localhost:8000" target="_blank">http://localhost:8000</a></p>

        <h6 class="fw-bold mt-4">7.2 Menggunakan Laragon (Alternatif)</h6>
        <p>Jika menggunakan Laragon, cukup:</p>
        <ol class="small">
            <li>Pastikan folder project ada di <code>C:\laragon\www\</code></li>
            <li>Start Laragon (Apache + MySQL)</li>
            <li>Akses via: <code>http://prestasi-siswa-svm.test</code> atau <code>http://localhost/prestasi-siswa-svm/public</code></li>
        </ol>
    </div>
</div>

{{-- Login Credentials --}}
<div class="card mb-4">
    <div class="card-header bg-warning">
        <h5 class="mb-0"><i class="bi bi-key me-2"></i>8. Akun Login Default</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-shield-lock me-2"></i>Administrator</h6>
                        <table class="table table-sm mb-0">
                            <tr>
                                <td>Email:</td>
                                <td><code>admin@sman2bukittinggi.sch.id</code></td>
                            </tr>
                            <tr>
                                <td>Password:</td>
                                <td><code>password</code></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-person-badge me-2"></i>Guru</h6>
                        <table class="table table-sm mb-0">
                            <tr>
                                <td>Email:</td>
                                <td><code>ahmad.syafii@sman2bukittinggi.sch.id</code></td>
                            </tr>
                            <tr>
                                <td>Password:</td>
                                <td><code>password</code></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-danger mt-3 small mb-0">
            <i class="bi bi-exclamation-octagon me-2"></i>
            <strong>PENTING:</strong> Segera ganti password default setelah login ke production!
        </div>
    </div>
</div>

{{-- Quick Commands Reference --}}
<div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0"><i class="bi bi-terminal me-2"></i>Quick Reference Commands</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-light">
                    <tr>
                        <th width="40%">Command</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>php artisan serve</code></td>
                        <td>Jalankan development server</td>
                    </tr>
                    <tr>
                        <td><code>php artisan migrate</code></td>
                        <td>Jalankan database migrations</td>
                    </tr>
                    <tr>
                        <td><code>php artisan migrate:fresh --seed</code></td>
                        <td>Reset database dan isi data awal</td>
                    </tr>
                    <tr>
                        <td><code>php artisan db:seed</code></td>
                        <td>Jalankan database seeders</td>
                    </tr>
                    <tr>
                        <td><code>php artisan ml:train --activate</code></td>
                        <td>Train dan aktifkan model SVM</td>
                    </tr>
                    <tr>
                        <td><code>php artisan cache:clear</code></td>
                        <td>Hapus cache aplikasi</td>
                    </tr>
                    <tr>
                        <td><code>php artisan config:clear</code></td>
                        <td>Hapus cache konfigurasi</td>
                    </tr>
                    <tr>
                        <td><code>php artisan route:clear</code></td>
                        <td>Hapus cache routes</td>
                    </tr>
                    <tr>
                        <td><code>php artisan optimize:clear</code></td>
                        <td>Hapus semua cache sekaligus</td>
                    </tr>
                    <tr>
                        <td><code>composer dump-autoload</code></td>
                        <td>Regenerate autoload files</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Troubleshooting --}}
<div class="card mb-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0"><i class="bi bi-bug me-2"></i>Troubleshooting</h5>
    </div>
    <div class="card-body">
        <div class="accordion" id="troubleshootingAccordion">
            {{-- Issue 1 --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issue1">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        Error: "Could not find driver"
                    </button>
                </h2>
                <div id="issue1" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                    <div class="accordion-body">
                        <p><strong>Penyebab:</strong> Extension PDO MySQL tidak aktif.</p>
                        <p><strong>Solusi:</strong></p>
                        <ol class="small">
                            <li>Buka file <code>php.ini</code></li>
                            <li>Cari dan uncomment (hapus ;) baris: <code>;extension=pdo_mysql</code></li>
                            <li>Restart Apache/Laragon</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- Issue 2 --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issue2">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        Error: "Permission denied" pada storage/logs
                    </button>
                </h2>
                <div id="issue2" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                    <div class="accordion-body">
                        <p><strong>Penyebab:</strong> Folder storage tidak memiliki permission write.</p>
                        <p><strong>Solusi:</strong></p>
                        <div class="bg-dark text-light p-2 rounded small">
                            <code class="text-info"># Windows - jalankan sebagai Administrator:</code><br>
                            <code>icacls storage /grant Everyone:F /T</code><br>
                            <code>icacls bootstrap/cache /grant Everyone:F /T</code><br><br>
                            <code class="text-info"># Linux/Mac:</code><br>
                            <code>chmod -R 775 storage bootstrap/cache</code>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Issue 3 --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issue3">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        Error: "Class not found"
                    </button>
                </h2>
                <div id="issue3" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                    <div class="accordion-body">
                        <p><strong>Penyebab:</strong> Autoload belum di-regenerate.</p>
                        <p><strong>Solusi:</strong></p>
                        <div class="bg-dark text-light p-2 rounded small">
                            <code>composer dump-autoload</code><br>
                            <code>php artisan optimize:clear</code>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Issue 4 --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issue4">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        Error: "SQLSTATE[HY000] [1045] Access denied"
                    </button>
                </h2>
                <div id="issue4" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                    <div class="accordion-body">
                        <p><strong>Penyebab:</strong> Kredensial database salah di file .env.</p>
                        <p><strong>Solusi:</strong></p>
                        <ol class="small">
                            <li>Periksa <code>DB_USERNAME</code> dan <code>DB_PASSWORD</code> di file <code>.env</code></li>
                            <li>Pastikan user MySQL memiliki akses ke database</li>
                            <li>Jalankan <code>php artisan config:clear</code> setelah mengubah .env</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- Issue 5 --}}
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#issue5">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                        Halaman blank / error 500
                    </button>
                </h2>
                <div id="issue5" class="accordion-collapse collapse" data-bs-parent="#troubleshootingAccordion">
                    <div class="accordion-body">
                        <p><strong>Penyebab:</strong> Error tidak terlihat karena APP_DEBUG=false atau log tidak writable.</p>
                        <p><strong>Solusi:</strong></p>
                        <ol class="small">
                            <li>Set <code>APP_DEBUG=true</code> di file .env</li>
                            <li>Cek file <code>storage/logs/laravel.log</code> untuk detail error</li>
                            <li>Jalankan <code>php artisan optimize:clear</code></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Folder Structure --}}
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="bi bi-folder-fill me-2"></i>Struktur Folder Penting</h5>
    </div>
    <div class="card-body">
        <div class="bg-light border rounded p-3">
<pre class="mb-0 small"><code>prestasi-siswa-svm/
├── app/
│   ├── Http/Controllers/     # Controller files
│   ├── Models/               # Eloquent models
│   └── Services/SVM/         # SVM implementation (PHP native)
├── config/                   # Configuration files
├── database/
│   ├── migrations/           # Database schema
│   └── seeders/              # Sample data
├── public/                   # Web root (index.php)
├── resources/views/          # Blade templates
├── routes/web.php            # Route definitions
├── storage/
│   ├── app/ml-models/        # Trained SVM models (.json)
│   └── logs/                 # Application logs
├── .env                      # Environment config (create from .env.example)
└── composer.json             # PHP dependencies</code></pre>
        </div>
    </div>
</div>

{{-- Complete Setup Script --}}
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-file-code me-2"></i>Script Setup Lengkap (Copy-Paste)</h5>
    </div>
    <div class="card-body">
        <p>Copy dan jalankan perintah berikut secara berurutan:</p>

        <div class="bg-dark text-light p-3 rounded">
<pre class="text-light mb-0"><code><span class="text-info"># 1. Clone repository</span>
git clone https://github.com/[username]/prestasi-siswa-svm.git
cd prestasi-siswa-svm

<span class="text-info"># 2. Install dependencies</span>
composer install

<span class="text-info"># 3. Setup environment</span>
copy .env.example .env
php artisan key:generate

<span class="text-info"># 4. Edit .env file (set database credentials)</span>
<span class="text-warning"># Buka .env dengan text editor dan sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD</span>

<span class="text-info"># 5. Database setup</span>
php artisan migrate:fresh --seed

<span class="text-info"># 6. Train ML model</span>
php artisan ml:train --activate

<span class="text-info"># 7. Run application</span>
php artisan serve

<span class="text-success"># Akses: http://localhost:8000</span>
<span class="text-success"># Login: admin@sman2bukittinggi.sch.id / password</span></code></pre>
        </div>

        <div class="mt-4 p-3 bg-success bg-opacity-10 border border-success rounded">
            <h6 class="text-success mb-2"><i class="bi bi-check-circle me-2"></i>Setup Selesai!</h6>
            <p class="mb-0 small">Jika semua langkah berhasil, aplikasi siap digunakan. Akses
            <a href="http://localhost:8000" target="_blank">http://localhost:8000</a> dan login dengan kredensial di atas.</p>
        </div>
    </div>
</div>
@endsection
