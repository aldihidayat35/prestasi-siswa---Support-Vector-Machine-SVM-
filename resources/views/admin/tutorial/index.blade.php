@extends('layouts.app')

@section('title', 'Tutorial Aplikasi')

@push('styles')
<style>
    .tutorial-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .tutorial-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    .tutorial-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .progress-tracker {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }

    .progress-tracker::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 4px;
        background: #e9ecef;
        z-index: 0;
    }

    .progress-tracker .progress-line {
        position: absolute;
        top: 20px;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        z-index: 1;
        transition: width 0.5s ease;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 2;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .progress-step:hover {
        transform: translateY(-3px);
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #6c757d;
        transition: all 0.3s ease;
        border: 3px solid white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .progress-step.active .step-circle,
    .progress-step.completed .step-circle {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .progress-step.completed .step-circle {
        background: #28a745;
    }

    .step-label {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #6c757d;
        text-align: center;
        max-width: 80px;
    }

    .tutorial-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .tutorial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .tutorial-card .card-header {
        background: white;
        border-bottom: 1px solid #f0f0f0;
        padding: 1.25rem 1.5rem;
        cursor: pointer;
    }

    .tutorial-card .card-header:hover {
        background: #f8f9fa;
    }

    .step-number {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .step-check {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .step-check.checked {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }

    .step-check:hover {
        border-color: #28a745;
        transform: scale(1.1);
    }

    .code-block {
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        font-family: 'Consolas', 'Monaco', monospace;
        font-size: 0.9rem;
        position: relative;
        margin: 1rem 0;
    }

    .code-block .copy-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(255,255,255,0.1);
        border: none;
        color: #d4d4d4;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        cursor: pointer;
        font-size: 0.75rem;
    }

    .code-block .copy-btn:hover {
        background: rgba(255,255,255,0.2);
    }

    .code-block .keyword { color: #569cd6; }
    .code-block .string { color: #ce9178; }
    .code-block .comment { color: #6a9955; }
    .code-block .command { color: #dcdcaa; }

    .feature-demo {
        background: #f8f9fa;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin: 1rem 0;
        border: 2px dashed #dee2e6;
        text-align: center;
    }

    .feature-demo i {
        font-size: 3rem;
        color: #667eea;
        margin-bottom: 1rem;
    }

    .quick-action-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .tip-box {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
        border-left: 4px solid #ffc107;
        padding: 1rem 1.25rem;
        border-radius: 0 0.5rem 0.5rem 0;
        margin: 1rem 0;
    }

    .tip-box.info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        border-left-color: #17a2b8;
    }

    .tip-box.success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left-color: #28a745;
    }

    .video-placeholder {
        background: linear-gradient(135deg, #2d3436 0%, #636e72 100%);
        border-radius: 0.75rem;
        padding: 3rem;
        text-align: center;
        color: white;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .video-placeholder:hover {
        transform: scale(1.02);
    }

    .video-placeholder .play-btn {
        width: 70px;
        height: 70px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        transition: all 0.3s ease;
    }

    .video-placeholder:hover .play-btn {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }

    .completion-badge {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 1rem;
        z-index: 1000;
        animation: slideUp 0.5s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #667eea15, #764ba215);
        color: #667eea;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: #667eea;
    }

    .nav-pills .nav-link {
        border-radius: 2rem;
        padding: 0.5rem 1.25rem;
        color: #6c757d;
        font-weight: 500;
    }

    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .floating-help {
        position: fixed;
        bottom: 2rem;
        left: 2rem;
        z-index: 1000;
    }

    .floating-help button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }

    .floating-help button:hover {
        transform: scale(1.1) rotate(15deg);
    }

    .screenshot-frame {
        border: 3px solid #dee2e6;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .screenshot-frame .frame-header {
        background: #f0f0f0;
        padding: 0.5rem 1rem;
        display: flex;
        gap: 0.5rem;
    }

    .screenshot-frame .frame-header .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .screenshot-frame .frame-header .dot.red { background: #ff5f56; }
    .screenshot-frame .frame-header .dot.yellow { background: #ffbd2e; }
    .screenshot-frame .frame-header .dot.green { background: #27c93f; }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="tutorial-hero">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-3">
                <i class="bi bi-mortarboard me-2"></i>
                Panduan Penggunaan Aplikasi
            </h2>
            <p class="mb-4 opacity-90">
                Pelajari cara menggunakan Sistem Prediksi Prestasi Akademik Siswa dengan SVM
                melalui tutorial interaktif ini. Ikuti setiap langkah untuk memaksimalkan penggunaan aplikasi.
            </p>
            <div class="d-flex gap-3 flex-wrap">
                <span class="badge bg-white text-primary px-3 py-2">
                    <i class="bi bi-clock me-1"></i> Estimasi: 10 menit
                </span>
                <span class="badge bg-white text-primary px-3 py-2">
                    <i class="bi bi-list-check me-1"></i> 6 Langkah
                </span>
                <span class="badge bg-white text-primary px-3 py-2">
                    <i class="bi bi-star me-1"></i> Pemula
                </span>
            </div>
        </div>
        <div class="col-lg-4 text-center d-none d-lg-block">
            <i class="bi bi-journal-bookmark display-1 opacity-75"></i>
        </div>
    </div>
</div>

<!-- Progress Tracker -->
<div class="card tutorial-card mb-4">
    <div class="card-body p-4">
        <h5 class="mb-4">
            <i class="bi bi-graph-up-arrow me-2 text-primary"></i>
            Progress Tutorial Anda
        </h5>
        <div class="progress-tracker">
            <div class="progress-line" id="progressLine" style="width: 0%"></div>
            <div class="progress-step completed" data-step="0" onclick="scrollToStep(1)">
                <div class="step-circle"><i class="bi bi-check"></i></div>
                <span class="step-label">Mulai</span>
            </div>
            <div class="progress-step" data-step="1" onclick="scrollToStep(1)">
                <div class="step-circle">1</div>
                <span class="step-label">Login</span>
            </div>
            <div class="progress-step" data-step="2" onclick="scrollToStep(2)">
                <div class="step-circle">2</div>
                <span class="step-label">Data Siswa</span>
            </div>
            <div class="progress-step" data-step="3" onclick="scrollToStep(3)">
                <div class="step-circle">3</div>
                <span class="step-label">Aktivitas</span>
            </div>
            <div class="progress-step" data-step="4" onclick="scrollToStep(4)">
                <div class="step-circle">4</div>
                <span class="step-label">Training</span>
            </div>
            <div class="progress-step" data-step="5" onclick="scrollToStep(5)">
                <div class="step-circle">5</div>
                <span class="step-label">Prediksi</span>
            </div>
            <div class="progress-step" data-step="6" onclick="scrollToStep(6)">
                <div class="step-circle">6</div>
                <span class="step-label">Selesai</span>
            </div>
        </div>
        <div class="text-center mt-3">
            <span class="text-muted" id="progressText">0 dari 6 langkah selesai</span>
            <div class="progress mt-2" style="height: 8px;">
                <div class="progress-bar bg-gradient" id="progressBar" style="width: 0%; background: linear-gradient(90deg, #667eea, #764ba2);"></div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Navigation -->
<div class="card tutorial-card mb-4">
    <div class="card-body p-3">
        <ul class="nav nav-pills justify-content-center flex-wrap gap-2" id="tutorialTabs">
            <li class="nav-item">
                <a class="nav-link active" href="#" onclick="filterSteps('all')">
                    <i class="bi bi-grid me-1"></i> Semua
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="filterSteps('basic')">
                    <i class="bi bi-star me-1"></i> Dasar
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="filterSteps('data')">
                    <i class="bi bi-database me-1"></i> Data
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="filterSteps('ml')">
                    <i class="bi bi-robot me-1"></i> Machine Learning
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Tutorial Steps -->
<div class="tutorial-steps">

    <!-- Step 1: Login -->
    <div class="card tutorial-card mb-4 step-card" data-step="1" data-category="basic" id="step1">
        <div class="card-header d-flex align-items-center justify-content-between"
             data-bs-toggle="collapse" data-bs-target="#collapse1">
            <div class="d-flex align-items-center gap-3">
                <div class="step-number">1</div>
                <div>
                    <h5 class="mb-0">Login ke Sistem</h5>
                    <small class="text-muted">Akses aplikasi dengan akun yang valid</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-success-subtle text-success">Dasar</span>
                <div class="step-check" onclick="event.stopPropagation(); toggleStep(1)">
                    <i class="bi bi-check"></i>
                </div>
            </div>
        </div>
        <div class="collapse show" id="collapse1">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <p>Untuk mengakses sistem, Anda perlu login dengan akun yang sudah terdaftar.</p>

                        <div class="tip-box info">
                            <strong><i class="bi bi-info-circle me-2"></i>Akun Default:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Admin:</strong> admin@sman2bukittinggi.sch.id</li>
                                <li><strong>Guru:</strong> ahmad.syafii@sman2bukittinggi.sch.id</li>
                                <li><strong>Password:</strong> password</li>
                            </ul>
                        </div>

                        <h6 class="mt-4 mb-3"><i class="bi bi-list-ol me-2"></i>Langkah-langkah:</h6>
                        <ol class="mb-4">
                            <li class="mb-2">Buka browser dan akses <code>http://localhost:8000</code></li>
                            <li class="mb-2">Masukkan email pada field "Email"</li>
                            <li class="mb-2">Masukkan password pada field "Password"</li>
                            <li class="mb-2">Klik tombol <strong>"Masuk"</strong></li>
                        </ol>

                        <a href="{{ route('login') }}" target="_blank" class="btn btn-primary quick-action-btn">
                            <i class="bi bi-box-arrow-in-right"></i> Buka Halaman Login
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <div class="screenshot-frame">
                            <div class="frame-header">
                                <div class="dot red"></div>
                                <div class="dot yellow"></div>
                                <div class="dot green"></div>
                            </div>
                            <div class="feature-demo" style="border-radius: 0;">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <h6>Halaman Login</h6>
                                <p class="text-muted small mb-0">Form login dengan validasi email & password</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 2: Data Siswa -->
    <div class="card tutorial-card mb-4 step-card" data-step="2" data-category="data" id="step2">
        <div class="card-header d-flex align-items-center justify-content-between"
             data-bs-toggle="collapse" data-bs-target="#collapse2">
            <div class="d-flex align-items-center gap-3">
                <div class="step-number">2</div>
                <div>
                    <h5 class="mb-0">Kelola Data Siswa</h5>
                    <small class="text-muted">Tambah, edit, dan kelola data siswa</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-info-subtle text-info">Data</span>
                <div class="step-check" onclick="event.stopPropagation(); toggleStep(2)">
                    <i class="bi bi-check"></i>
                </div>
            </div>
        </div>
        <div class="collapse" id="collapse2">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-7">
                        <p>Data siswa adalah fondasi dari sistem prediksi. Pastikan data siswa lengkap dan akurat.</p>

                        <div class="accordion" id="studentAccordion">
                            <div class="accordion-item border-0 mb-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#addStudent">
                                        <i class="bi bi-plus-circle me-2 text-success"></i> Menambah Siswa Baru
                                    </button>
                                </h2>
                                <div id="addStudent" class="accordion-collapse collapse" data-bs-parent="#studentAccordion">
                                    <div class="accordion-body">
                                        <ol>
                                            <li>Buka menu <strong>Data Siswa</strong></li>
                                            <li>Klik tombol <span class="badge bg-primary">+ Tambah Siswa</span></li>
                                            <li>Isi form: NISN, Nama, Kelas, Jenis Kelamin, dll</li>
                                            <li>Klik <strong>Simpan</strong></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 mb-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#importStudent">
                                        <i class="bi bi-upload me-2 text-info"></i> Import dari Excel
                                    </button>
                                </h2>
                                <div id="importStudent" class="accordion-collapse collapse" data-bs-parent="#studentAccordion">
                                    <div class="accordion-body">
                                        <ol>
                                            <li>Siapkan file Excel dengan format yang benar</li>
                                            <li>Klik tombol <span class="badge bg-info">Import</span></li>
                                            <li>Pilih file dan upload</li>
                                            <li>Sistem akan memvalidasi dan menyimpan data</li>
                                        </ol>
                                        <div class="code-block">
                                            <button class="copy-btn" onclick="copyCode(this)">
                                                <i class="bi bi-clipboard"></i> Copy
                                            </button>
                                            <span class="comment"># Format kolom Excel:</span><br>
                                            NISN | Nama | Kelas | JK | Alamat | No HP
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tip-box success mt-3">
                            <strong><i class="bi bi-lightbulb me-2"></i>Tips:</strong>
                            Pastikan NISN unik untuk setiap siswa. Sistem akan menolak duplikat.
                        </div>

                        <a href="{{ route('admin.students.index') }}" class="btn btn-primary quick-action-btn mt-3">
                            <i class="bi bi-people"></i> Kelola Data Siswa
                        </a>
                    </div>
                    <div class="col-lg-5">
                        <div class="feature-demo">
                            <i class="bi bi-people-fill"></i>
                            <h6>Manajemen Siswa</h6>
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                <span class="badge bg-primary">CRUD</span>
                                <span class="badge bg-info">Import</span>
                                <span class="badge bg-success">Export</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 3: Aktivitas Belajar -->
    <div class="card tutorial-card mb-4 step-card" data-step="3" data-category="data" id="step3">
        <div class="card-header d-flex align-items-center justify-content-between"
             data-bs-toggle="collapse" data-bs-target="#collapse3">
            <div class="d-flex align-items-center gap-3">
                <div class="step-number">3</div>
                <div>
                    <h5 class="mb-0">Input Aktivitas Belajar</h5>
                    <small class="text-muted">Catat 6 indikator aktivitas belajar siswa</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-info-subtle text-info">Data</span>
                <div class="step-check" onclick="event.stopPropagation(); toggleStep(3)">
                    <i class="bi bi-check"></i>
                </div>
            </div>
        </div>
        <div class="collapse" id="collapse3">
            <div class="card-body">
                <p>Aktivitas belajar adalah <strong>fitur utama</strong> yang digunakan SVM untuk memprediksi prestasi akademik.</p>

                <h6 class="mt-4 mb-3"><i class="bi bi-graph-up me-2"></i>6 Indikator Aktivitas:</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100 text-center">
                            <i class="bi bi-calendar-check text-primary fs-3"></i>
                            <h6 class="mt-2 mb-1">Kehadiran</h6>
                            <small class="text-muted">0-100%</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100 text-center">
                            <i class="bi bi-clock text-success fs-3"></i>
                            <h6 class="mt-2 mb-1">Durasi Belajar</h6>
                            <small class="text-muted">0-8 jam/hari</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100 text-center">
                            <i class="bi bi-journal-text text-info fs-3"></i>
                            <h6 class="mt-2 mb-1">Frekuensi Tugas</h6>
                            <small class="text-muted">0-50 tugas</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100 text-center">
                            <i class="bi bi-chat-dots text-warning fs-3"></i>
                            <h6 class="mt-2 mb-1">Partisipasi Diskusi</h6>
                            <small class="text-muted">0-100%</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100 text-center">
                            <i class="bi bi-laptop text-danger fs-3"></i>
                            <h6 class="mt-2 mb-1">Penggunaan Media</h6>
                            <small class="text-muted">0-100%</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100 text-center">
                            <i class="bi bi-shield-check text-secondary fs-3"></i>
                            <h6 class="mt-2 mb-1">Kedisiplinan</h6>
                            <small class="text-muted">0-100%</small>
                        </div>
                    </div>
                </div>

                <div class="tip-box mt-4">
                    <strong><i class="bi bi-exclamation-triangle me-2"></i>Penting:</strong>
                    Data aktivitas belajar harus diinput secara berkala (mingguan/bulanan) untuk hasil prediksi yang akurat.
                </div>

                <a href="{{ route('admin.learning-activities.create') }}" class="btn btn-primary quick-action-btn mt-3">
                    <i class="bi bi-plus-lg"></i> Input Aktivitas Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Step 4: Training Model -->
    <div class="card tutorial-card mb-4 step-card" data-step="4" data-category="ml" id="step4">
        <div class="card-header d-flex align-items-center justify-content-between"
             data-bs-toggle="collapse" data-bs-target="#collapse4">
            <div class="d-flex align-items-center gap-3">
                <div class="step-number">4</div>
                <div>
                    <h5 class="mb-0">Training Model SVM</h5>
                    <small class="text-muted">Latih model machine learning dengan data yang ada</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-warning-subtle text-warning">ML</span>
                <div class="step-check" onclick="event.stopPropagation(); toggleStep(4)">
                    <i class="bi bi-check"></i>
                </div>
            </div>
        </div>
        <div class="collapse" id="collapse4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-7">
                        <p>Setelah data cukup (minimal 10 sampel), Anda dapat melatih model SVM.</p>

                        <h6 class="mb-3"><i class="bi bi-gear me-2"></i>Parameter Training:</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Kernel</strong></td>
                                <td>RBF (default), Linear, Polynomial</td>
                            </tr>
                            <tr>
                                <td><strong>C (Regularization)</strong></td>
                                <td>1.0 (default) - kontrol overfitting</td>
                            </tr>
                            <tr>
                                <td><strong>Gamma</strong></td>
                                <td>Auto - pengaruh jarak sampel</td>
                            </tr>
                            <tr>
                                <td><strong>Test Size</strong></td>
                                <td>20% - proporsi data testing</td>
                            </tr>
                        </table>

                        <div class="code-block">
                            <button class="copy-btn" onclick="copyCode(this)">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                            <span class="comment"># Training via CLI (opsional):</span><br>
                            <span class="command">php artisan ml:train</span> --activate
                        </div>

                        <div class="tip-box info">
                            <strong><i class="bi bi-info-circle me-2"></i>Info:</strong>
                            Setelah training, jangan lupa <strong>aktifkan model</strong> agar bisa digunakan untuk prediksi.
                        </div>

                        <a href="{{ route('admin.ml-models.create') }}" class="btn btn-primary quick-action-btn mt-3">
                            <i class="bi bi-cpu"></i> Training Model Baru
                        </a>
                    </div>
                    <div class="col-lg-5">
                        <div class="video-placeholder" onclick="playDemo()">
                            <div class="play-btn">
                                <i class="bi bi-play-fill fs-2"></i>
                            </div>
                            <h6>Demo Training</h6>
                            <small class="opacity-75">Klik untuk melihat proses training</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 5: Prediksi -->
    <div class="card tutorial-card mb-4 step-card" data-step="5" data-category="ml" id="step5">
        <div class="card-header d-flex align-items-center justify-content-between"
             data-bs-toggle="collapse" data-bs-target="#collapse5">
            <div class="d-flex align-items-center gap-3">
                <div class="step-number">5</div>
                <div>
                    <h5 class="mb-0">Melakukan Prediksi</h5>
                    <small class="text-muted">Prediksi prestasi akademik siswa</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-warning-subtle text-warning">ML</span>
                <div class="step-check" onclick="event.stopPropagation(); toggleStep(5)">
                    <i class="bi bi-check"></i>
                </div>
            </div>
        </div>
        <div class="collapse" id="collapse5">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <p>Dengan model yang sudah dilatih, Anda dapat memprediksi prestasi akademik siswa.</p>

                        <h6 class="mb-3"><i class="bi bi-list-ol me-2"></i>Langkah Prediksi:</h6>
                        <ol>
                            <li class="mb-2">Buka menu <strong>Prediksi → Buat Prediksi</strong></li>
                            <li class="mb-2">Pilih siswa yang akan diprediksi</li>
                            <li class="mb-2">Input 6 indikator aktivitas belajar</li>
                            <li class="mb-2">Klik tombol <strong>"Prediksi Sekarang"</strong></li>
                            <li class="mb-2">Lihat hasil: <span class="badge bg-danger">Rendah</span> <span class="badge bg-warning">Sedang</span> <span class="badge bg-success">Tinggi</span></li>
                        </ol>

                        <div class="tip-box success">
                            <strong><i class="bi bi-lightbulb me-2"></i>Fitur Tambahan:</strong>
                            Setiap prediksi dilengkapi dengan <strong>rekomendasi akademik</strong> yang dapat membantu guru memberikan bimbingan.
                        </div>

                        <a href="{{ route('admin.predictions.create') }}" class="btn btn-success quick-action-btn mt-3">
                            <i class="bi bi-magic"></i> Buat Prediksi Sekarang
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <div class="feature-demo" style="background: linear-gradient(135deg, #d4edda, #c3e6cb);">
                            <i class="bi bi-graph-up-arrow text-success"></i>
                            <h6 class="text-success">Output Prediksi</h6>
                            <div class="mt-3">
                                <div class="row g-2 text-center">
                                    <div class="col-4">
                                        <div class="bg-white rounded p-2">
                                            <strong class="text-danger">Rendah</strong>
                                            <br><small>&lt; 60</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-white rounded p-2">
                                            <strong class="text-warning">Sedang</strong>
                                            <br><small>60-79</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-white rounded p-2">
                                            <strong class="text-success">Tinggi</strong>
                                            <br><small>≥ 80</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 6: Selesai -->
    <div class="card tutorial-card mb-4 step-card" data-step="6" data-category="basic" id="step6">
        <div class="card-header d-flex align-items-center justify-content-between"
             data-bs-toggle="collapse" data-bs-target="#collapse6">
            <div class="d-flex align-items-center gap-3">
                <div class="step-number" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <i class="bi bi-trophy"></i>
                </div>
                <div>
                    <h5 class="mb-0">Selamat! Tutorial Selesai 🎉</h5>
                    <small class="text-muted">Anda siap menggunakan aplikasi secara penuh</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-success-subtle text-success">Selesai</span>
                <div class="step-check" onclick="event.stopPropagation(); toggleStep(6)">
                    <i class="bi bi-check"></i>
                </div>
            </div>
        </div>
        <div class="collapse" id="collapse6">
            <div class="card-body text-center py-5">
                <div class="display-1 text-success mb-3">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <h3 class="mb-3">Anda Telah Menguasai Dasar-dasar Aplikasi!</h3>
                <p class="text-muted mb-4">
                    Jelajahi fitur lainnya dan mulai prediksi prestasi akademik siswa.
                </p>

                <div class="row g-3 justify-content-center mb-4">
                    <div class="col-auto">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary quick-action-btn">
                            <i class="bi bi-speedometer2"></i> Ke Dashboard
                        </a>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.svm-explanation') }}" class="btn btn-outline-primary quick-action-btn">
                            <i class="bi bi-book"></i> Pelajari SVM
                        </a>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.predictions.create') }}" class="btn btn-success quick-action-btn">
                            <i class="bi bi-magic"></i> Buat Prediksi
                        </a>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="mb-3">Butuh Bantuan?</h6>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="#" class="text-decoration-none">
                        <i class="bi bi-file-earmark-pdf text-danger me-1"></i> Dokumentasi PDF
                    </a>
                    <a href="#" class="text-decoration-none">
                        <i class="bi bi-envelope text-primary me-1"></i> Hubungi Support
                    </a>
                    <a href="#" class="text-decoration-none">
                        <i class="bi bi-github text-dark me-1"></i> Source Code
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Help Button -->
<div class="floating-help">
    <button type="button" onclick="resetProgress()" title="Reset Progress">
        <i class="bi bi-arrow-counterclockwise"></i>
    </button>
</div>

<!-- Completion Badge (Hidden by default) -->
<div class="completion-badge d-none" id="completionBadge">
    <div class="text-success">
        <i class="bi bi-trophy-fill fs-3"></i>
    </div>
    <div>
        <strong>Tutorial Selesai!</strong>
        <div class="text-muted small">Semua langkah telah dipelajari</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // State management
    let completedSteps = JSON.parse(localStorage.getItem('tutorialProgress') || '[]');

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        updateProgress();
        restoreState();
    });

    function toggleStep(stepNum) {
        const index = completedSteps.indexOf(stepNum);
        if (index > -1) {
            completedSteps.splice(index, 1);
        } else {
            completedSteps.push(stepNum);
        }

        localStorage.setItem('tutorialProgress', JSON.stringify(completedSteps));
        updateProgress();
        updateStepUI(stepNum);
    }

    function updateStepUI(stepNum) {
        const stepCard = document.querySelector(`[data-step="${stepNum}"]`);
        const checkBox = stepCard.querySelector('.step-check');
        const progressStep = document.querySelector(`.progress-step[data-step="${stepNum}"]`);

        if (completedSteps.includes(stepNum)) {
            checkBox.classList.add('checked');
            if (progressStep) {
                progressStep.classList.add('completed');
            }
        } else {
            checkBox.classList.remove('checked');
            if (progressStep) {
                progressStep.classList.remove('completed');
            }
        }
    }

    function updateProgress() {
        const total = 6;
        const completed = completedSteps.length;
        const percentage = (completed / total) * 100;

        // Update progress bar
        document.getElementById('progressBar').style.width = percentage + '%';
        document.getElementById('progressLine').style.width = percentage + '%';
        document.getElementById('progressText').textContent = `${completed} dari ${total} langkah selesai`;

        // Update step indicators
        for (let i = 1; i <= total; i++) {
            updateStepUI(i);
        }

        // Show completion badge
        if (completed === total) {
            document.getElementById('completionBadge').classList.remove('d-none');
        } else {
            document.getElementById('completionBadge').classList.add('d-none');
        }
    }

    function restoreState() {
        completedSteps.forEach(stepNum => {
            updateStepUI(stepNum);
        });
    }

    function scrollToStep(stepNum) {
        const element = document.getElementById('step' + stepNum);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Open the collapse
            const collapse = element.querySelector('.collapse');
            if (collapse && !collapse.classList.contains('show')) {
                new bootstrap.Collapse(collapse, { show: true });
            }
        }
    }

    function filterSteps(category) {
        // Update nav
        document.querySelectorAll('#tutorialTabs .nav-link').forEach(link => {
            link.classList.remove('active');
        });
        event.target.classList.add('active');

        // Filter cards
        document.querySelectorAll('.step-card').forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function resetProgress() {
        if (confirm('Reset semua progress tutorial?')) {
            completedSteps = [];
            localStorage.removeItem('tutorialProgress');
            updateProgress();
            location.reload();
        }
    }

    function copyCode(btn) {
        const codeBlock = btn.parentElement;
        const text = codeBlock.textContent.replace('Copy', '').trim();
        navigator.clipboard.writeText(text);

        btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-clipboard"></i> Copy';
        }, 2000);
    }

    function playDemo() {
        alert('Demo video akan ditambahkan pada versi selanjutnya!');
    }
</script>
@endpush
