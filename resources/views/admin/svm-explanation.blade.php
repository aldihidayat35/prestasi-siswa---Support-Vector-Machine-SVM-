@extends('layouts.app')

@section('title', 'Penjelasan Algoritma SVM')

@section('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin: -1.5rem -1.5rem 2rem -1.5rem;
    }

    .content-card {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .content-card .card-header {
        background: linear-gradient(135deg, #1e1e2d 0%, #1a1a27 100%);
        color: #fff;
        border-radius: 0.75rem 0.75rem 0 0;
        padding: 1.25rem 1.5rem;
    }

    .content-card .card-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .content-card .card-body {
        padding: 1.5rem;
    }

    .formula-box {
        background: #f8f9fc;
        border: 1px solid #e3e6f0;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin: 1rem 0;
        font-family: 'Times New Roman', serif;
        font-size: 1.1rem;
        text-align: center;
    }

    .formula-box .formula {
        font-style: italic;
        color: #1e1e2d;
    }

    .step-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .step-item {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .step-content h6 {
        color: #1e1e2d;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .kernel-card {
        border: 2px solid #e3e6f0;
        border-radius: 0.75rem;
        padding: 1.25rem;
        height: 100%;
        transition: all 0.3s ease;
    }

    .kernel-card:hover {
        border-color: #667eea;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.15);
    }

    .kernel-card h6 {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .kernel-card .formula {
        background: #f8f9fc;
        padding: 0.5rem;
        border-radius: 0.25rem;
        font-family: 'Times New Roman', serif;
        font-size: 0.95rem;
        margin: 0.75rem 0;
        text-align: center;
    }

    .feature-table th {
        background: #f8f9fc;
        font-weight: 600;
        color: #1e1e2d;
    }

    .advantage-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .advantage-item i {
        color: #28a745;
        font-size: 1.25rem;
        margin-top: 0.1rem;
    }

    .disadvantage-item i {
        color: #dc3545;
    }

    .nav-pills .nav-link {
        color: #5e6278;
        border-radius: 0.5rem;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
    }

    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .diagram-container {
        background: #f8f9fc;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
    }

    .diagram-container svg {
        max-width: 100%;
    }

    .toc-sidebar {
        position: sticky;
        top: 1rem;
    }

    .toc-sidebar .list-group-item {
        border: none;
        padding: 0.5rem 1rem;
        color: #5e6278;
        font-size: 0.9rem;
    }

    .toc-sidebar .list-group-item:hover,
    .toc-sidebar .list-group-item.active {
        background: #f8f9fc;
        color: #667eea;
        font-weight: 500;
    }

    .reference-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e3e6f0;
    }

    .reference-item:last-child {
        border-bottom: none;
    }

    .highlight-box {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-left: 4px solid #667eea;
        padding: 1rem 1.25rem;
        border-radius: 0 0.5rem 0.5rem 0;
        margin: 1rem 0;
    }

    .flowchart-step {
        background: white;
        border: 2px solid #e3e6f0;
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        position: relative;
    }

    .flowchart-step.active-step {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    }

    .flowchart-arrow {
        text-align: center;
        padding: 0.5rem 0;
        color: #667eea;
    }

    @media print {
        .hero-section {
            background: #667eea !important;
            -webkit-print-color-adjust: exact;
        }

        .no-print {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="bi bi-cpu me-3"></i>Support Vector Machine (SVM)
                </h1>
                <p class="lead mb-0 opacity-90">
                    Algoritma Machine Learning untuk Klasifikasi Prestasi Akademik Siswa
                    Berdasarkan Aktivitas Belajar di SMA Negeri 2 Bukittinggi
                </p>
            </div>
            <div class="col-lg-4 text-end d-none d-lg-block">
                <i class="bi bi-diagram-3" style="font-size: 8rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Table of Contents Sidebar -->
        <div class="col-lg-3 no-print">
            <div class="toc-sidebar">
                <div class="content-card">
                    <div class="card-header">
                        <h5><i class="bi bi-list-ul me-2"></i>Daftar Isi</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#pengertian" class="list-group-item list-group-item-action">1. Pengertian SVM</a>
                        <a href="#konsep" class="list-group-item list-group-item-action">2. Konsep Dasar</a>
                        <a href="#hyperplane" class="list-group-item list-group-item-action">3. Hyperplane & Margin</a>
                        <a href="#kernel" class="list-group-item list-group-item-action">4. Fungsi Kernel</a>
                        <a href="#algoritma" class="list-group-item list-group-item-action">5. Algoritma SMO</a>
                        <a href="#multiclass" class="list-group-item list-group-item-action">6. Klasifikasi Multi-class</a>
                        <a href="#parameter" class="list-group-item list-group-item-action">7. Parameter SVM</a>
                        <a href="#evaluasi" class="list-group-item list-group-item-action">8. Metrik Evaluasi</a>
                        <a href="#implementasi" class="list-group-item list-group-item-action">9. Implementasi</a>
                        <a href="#kelebihan" class="list-group-item list-group-item-action">10. Kelebihan & Kekurangan</a>
                        <a href="#referensi" class="list-group-item list-group-item-action">11. Referensi</a>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Cetak Halaman
                    </button>
                    <a href="{{ route('admin.ml-models.create') }}" class="btn btn-primary">
                        <i class="bi bi-play-circle me-2"></i>Training Model
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- 1. Pengertian SVM -->
            <section id="pengertian" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-info-circle me-2"></i>1. Pengertian Support Vector Machine</h5>
                </div>
                <div class="card-body">
                    <p class="lead">
                        <strong>Support Vector Machine (SVM)</strong> adalah algoritma pembelajaran mesin terawasi
                        (<em>supervised learning</em>) yang digunakan untuk tugas klasifikasi dan regresi.
                        SVM pertama kali diperkenalkan oleh Vladimir Vapnik dan rekan-rekannya pada tahun 1992.
                    </p>

                    <div class="highlight-box">
                        <strong><i class="bi bi-lightbulb me-2"></i>Prinsip Utama:</strong><br>
                        SVM bekerja dengan mencari <em>hyperplane</em> optimal yang memisahkan data dari
                        kelas yang berbeda dengan <strong>margin maksimal</strong>. Margin adalah jarak antara
                        hyperplane dengan titik data terdekat dari masing-masing kelas.
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6><i class="bi bi-check-circle text-success me-2"></i>Karakteristik SVM:</h6>
                            <ul>
                                <li>Efektif pada ruang dimensi tinggi</li>
                                <li>Bekerja baik dengan data yang limited</li>
                                <li>Menggunakan subset data (support vectors)</li>
                                <li>Fleksibel dengan berbagai fungsi kernel</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="bi bi-bullseye text-primary me-2"></i>Aplikasi SVM:</h6>
                            <ul>
                                <li>Klasifikasi teks dan dokumen</li>
                                <li>Pengenalan gambar</li>
                                <li>Diagnosis medis</li>
                                <li>Prediksi prestasi akademik</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. Konsep Dasar -->
            <section id="konsep" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-diagram-2 me-2"></i>2. Konsep Dasar SVM</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <h6>Ilustrasi Klasifikasi Biner</h6>
                            <p>
                                Pada klasifikasi biner, SVM mencari garis (2D) atau bidang (3D) atau hyperplane (n-D)
                                yang memisahkan dua kelas dengan margin terbesar. Titik-titik data yang berada
                                paling dekat dengan hyperplane disebut <strong>Support Vectors</strong>.
                            </p>

                            <p>
                                Tujuan SVM adalah memaksimalkan margin antara support vectors dari kedua kelas.
                                Semakin besar margin, semakin baik kemampuan generalisasi model.
                            </p>
                        </div>
                        <div class="col-md-5">
                            <div class="diagram-container">
                                <svg viewBox="0 0 300 250" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Background grid -->
                                    <defs>
                                        <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                                            <path d="M 20 0 L 0 0 0 20" fill="none" stroke="#e3e6f0" stroke-width="0.5"/>
                                        </pattern>
                                    </defs>
                                    <rect width="300" height="250" fill="url(#grid)"/>

                                    <!-- Hyperplane -->
                                    <line x1="50" y1="220" x2="250" y2="30" stroke="#667eea" stroke-width="3"/>

                                    <!-- Margin lines -->
                                    <line x1="30" y1="200" x2="230" y2="10" stroke="#667eea" stroke-width="1" stroke-dasharray="5,5"/>
                                    <line x1="70" y1="240" x2="270" y2="50" stroke="#667eea" stroke-width="1" stroke-dasharray="5,5"/>

                                    <!-- Margin annotation -->
                                    <text x="260" y="80" fill="#667eea" font-size="10">Margin</text>
                                    <line x1="245" y1="40" x2="255" y2="60" stroke="#667eea" stroke-width="1"/>

                                    <!-- Class 1 points (circles) -->
                                    <circle cx="80" cy="60" r="8" fill="#28a745"/>
                                    <circle cx="100" cy="80" r="8" fill="#28a745"/>
                                    <circle cx="60" cy="90" r="8" fill="#28a745"/>
                                    <circle cx="120" cy="50" r="8" fill="#28a745"/>
                                    <circle cx="90" cy="40" r="8" fill="#28a745"/>

                                    <!-- Support vector class 1 -->
                                    <circle cx="110" cy="100" r="10" fill="#28a745" stroke="#1e1e2d" stroke-width="2"/>

                                    <!-- Class 2 points (squares) -->
                                    <rect x="185" y="145" width="16" height="16" fill="#dc3545"/>
                                    <rect x="215" y="165" width="16" height="16" fill="#dc3545"/>
                                    <rect x="195" y="185" width="16" height="16" fill="#dc3545"/>
                                    <rect x="225" y="195" width="16" height="16" fill="#dc3545"/>
                                    <rect x="175" y="205" width="16" height="16" fill="#dc3545"/>

                                    <!-- Support vector class 2 -->
                                    <rect x="155" y="155" width="20" height="20" fill="#dc3545" stroke="#1e1e2d" stroke-width="2"/>

                                    <!-- Legend -->
                                    <circle cx="20" cy="235" r="6" fill="#28a745"/>
                                    <text x="30" y="238" font-size="10" fill="#1e1e2d">Kelas A</text>
                                    <rect x="75" y="229" width="12" height="12" fill="#dc3545"/>
                                    <text x="92" y="238" font-size="10" fill="#1e1e2d">Kelas B</text>
                                    <text x="140" y="238" font-size="10" fill="#1e1e2d">— Hyperplane</text>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-circle text-success" style="font-size: 2rem;"></i>
                                <h6 class="mt-2 mb-1">Support Vectors</h6>
                                <small class="text-muted">Titik data terdekat dengan hyperplane</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-arrows-expand text-primary" style="font-size: 2rem;"></i>
                                <h6 class="mt-2 mb-1">Margin</h6>
                                <small class="text-muted">Jarak antara hyperplane dan support vectors</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-slash-lg text-danger" style="font-size: 2rem;"></i>
                                <h6 class="mt-2 mb-1">Hyperplane</h6>
                                <small class="text-muted">Batas keputusan yang memisahkan kelas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. Hyperplane & Margin -->
            <section id="hyperplane" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-rulers me-2"></i>3. Hyperplane dan Margin</h5>
                </div>
                <div class="card-body">
                    <h6>Definisi Matematika Hyperplane</h6>
                    <p>
                        Hyperplane dalam SVM didefinisikan sebagai:
                    </p>

                    <div class="formula-box">
                        <span class="formula">w · x + b = 0</span>
                        <br><small class="text-muted mt-2 d-block">
                            di mana <strong>w</strong> = vektor bobot, <strong>x</strong> = vektor input, <strong>b</strong> = bias
                        </small>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6><i class="bi bi-hash me-2"></i>Hard Margin SVM</h6>
                            <p>
                                Digunakan ketika data dapat dipisahkan secara linear sempurna (linearly separable).
                                Tidak ada toleransi untuk kesalahan klasifikasi.
                            </p>
                            <div class="formula-box">
                                <span class="formula">y<sub>i</sub>(w · x<sub>i</sub> + b) ≥ 1, ∀i</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="bi bi-sliders me-2"></i>Soft Margin SVM</h6>
                            <p>
                                Memperbolehkan beberapa kesalahan klasifikasi dengan memperkenalkan variabel slack (ξ).
                                Lebih praktis untuk data dunia nyata.
                            </p>
                            <div class="formula-box">
                                <span class="formula">y<sub>i</sub>(w · x<sub>i</sub> + b) ≥ 1 - ξ<sub>i</sub>, ξ<sub>i</sub> ≥ 0</span>
                            </div>
                        </div>
                    </div>

                    <h6 class="mt-4"><i class="bi bi-bullseye me-2"></i>Fungsi Objektif</h6>
                    <p>
                        SVM mencari hyperplane optimal dengan meminimalkan:
                    </p>
                    <div class="formula-box">
                        <span class="formula">min (1/2)||w||² + C Σξ<sub>i</sub></span>
                        <br><small class="text-muted mt-2 d-block">
                            <strong>C</strong> = parameter regularisasi yang mengontrol trade-off antara margin dan error
                        </small>
                    </div>
                </div>
            </section>

            <!-- 4. Fungsi Kernel -->
            <section id="kernel" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-gear me-2"></i>4. Fungsi Kernel (Kernel Trick)</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Kernel trick</strong> adalah teknik yang memungkinkan SVM bekerja pada data yang
                        tidak dapat dipisahkan secara linear dengan memetakan data ke ruang dimensi yang lebih tinggi
                        tanpa perlu menghitung transformasi secara eksplisit.
                    </p>

                    <div class="highlight-box">
                        <strong><i class="bi bi-lightbulb me-2"></i>Ide Utama:</strong><br>
                        <code>K(x, y) = φ(x) · φ(y)</code><br>
                        Kernel menghitung dot product di ruang fitur tinggi tanpa perlu mengetahui fungsi φ secara eksplisit.
                    </div>

                    <h6 class="mt-4 mb-3">Jenis-jenis Kernel yang Tersedia:</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="kernel-card">
                                <h6><i class="bi bi-dash-lg me-2"></i>1. Linear Kernel</h6>
                                <p class="text-muted small mb-2">
                                    Paling sederhana, cocok untuk data yang linear separable.
                                </p>
                                <div class="formula">
                                    K(x, y) = x · y
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-success">Cepat</span>
                                    <span class="badge bg-info">Interpretable</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="kernel-card">
                                <h6><i class="bi bi-circle me-2"></i>2. RBF (Radial Basis Function)</h6>
                                <p class="text-muted small mb-2">
                                    Paling populer, cocok untuk sebagian besar kasus non-linear.
                                </p>
                                <div class="formula">
                                    K(x, y) = exp(-γ||x - y||²)
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-primary">Fleksibel</span>
                                    <span class="badge bg-warning text-dark">Default</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="kernel-card">
                                <h6><i class="bi bi-graph-up me-2"></i>3. Polynomial Kernel</h6>
                                <p class="text-muted small mb-2">
                                    Menangkap interaksi antar fitur dengan derajat tertentu.
                                </p>
                                <div class="formula">
                                    K(x, y) = (γ · x · y + r)<sup>d</sup>
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-secondary">Degree: d</span>
                                    <span class="badge bg-info">Interaksi Fitur</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="kernel-card">
                                <h6><i class="bi bi-activity me-2"></i>4. Sigmoid Kernel</h6>
                                <p class="text-muted small mb-2">
                                    Mirip dengan neural network, terkadang tidak memenuhi kondisi Mercer.
                                </p>
                                <div class="formula">
                                    K(x, y) = tanh(γ · x · y + r)
                                </div>
                                <div class="mt-2">
                                    <span class="badge bg-secondary">Neural-like</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 5. Algoritma SMO -->
            <section id="algoritma" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-code-square me-2"></i>5. Algoritma SMO (Sequential Minimal Optimization)</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>SMO</strong> adalah algoritma efisien untuk menyelesaikan masalah optimisasi kuadratik
                        (Quadratic Programming) dalam SVM. Dikembangkan oleh John Platt di Microsoft Research (1998).
                    </p>

                    <div class="highlight-box">
                        <strong><i class="bi bi-lightbulb me-2"></i>Ide Kunci SMO:</strong><br>
                        Memecah masalah QP besar menjadi sub-problem kecil dengan mengoptimalkan dua variabel
                        Lagrange multiplier (α) secara bersamaan pada setiap iterasi.
                    </div>

                    <h6 class="mt-4 mb-3">Langkah-langkah Algoritma SMO:</h6>

                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h6>Inisialisasi</h6>
                            <p class="mb-0 text-muted">
                                Set semua Lagrange multiplier α<sub>i</sub> = 0, dan bias b = 0.
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h6>Pilih Pasangan α untuk Optimisasi</h6>
                            <p class="mb-0 text-muted">
                                Gunakan heuristik untuk memilih α<sub>1</sub> yang melanggar kondisi KKT dan
                                α<sub>2</sub> yang memaksimalkan |E<sub>1</sub> - E<sub>2</sub>|.
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h6>Hitung Batas L dan H</h6>
                            <p class="mb-0 text-muted">
                                Tentukan batas bawah (L) dan batas atas (H) untuk α<sub>2</sub><sup>new</sup>
                                berdasarkan constraint box (0 ≤ α ≤ C).
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h6>Update α<sub>2</sub></h6>
                            <div class="formula-box">
                                <span class="formula">α<sub>2</sub><sup>new</sup> = α<sub>2</sub> + y<sub>2</sub>(E<sub>1</sub> - E<sub>2</sub>) / η</span>
                            </div>
                            <p class="mb-0 text-muted">
                                Di mana η = K<sub>11</sub> + K<sub>22</sub> - 2K<sub>12</sub>
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <h6>Clip α<sub>2</sub></h6>
                            <p class="mb-0 text-muted">
                                Pastikan α<sub>2</sub><sup>new,clipped</sup> berada dalam range [L, H].
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">6</div>
                        <div class="step-content">
                            <h6>Update α<sub>1</sub> dan b</h6>
                            <p class="mb-0 text-muted">
                                Hitung α<sub>1</sub><sup>new</sup> dan update threshold b berdasarkan kondisi KKT.
                            </p>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-number">7</div>
                        <div class="step-content">
                            <h6>Iterasi</h6>
                            <p class="mb-0 text-muted">
                                Ulangi langkah 2-6 sampai kondisi konvergensi tercapai atau maksimum iterasi.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 6. Klasifikasi Multi-class -->
            <section id="multiclass" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-diagram-3 me-2"></i>6. Klasifikasi Multi-class</h5>
                </div>
                <div class="card-body">
                    <p>
                        SVM secara native adalah classifier biner. Untuk klasifikasi multi-class
                        (seperti prediksi Rendah/Sedang/Tinggi), digunakan strategi:
                    </p>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="kernel-card border-primary">
                                <h6 class="text-primary"><i class="bi bi-check2-circle me-2"></i>One-vs-Rest (OvR)</h6>
                                <p class="small">
                                    <strong>Digunakan dalam sistem ini.</strong>
                                </p>
                                <p class="text-muted small mb-2">
                                    Untuk K kelas, buat K classifier biner. Setiap classifier membedakan
                                    satu kelas dari semua kelas lainnya.
                                </p>
                                <div class="bg-light p-2 rounded small">
                                    <strong>Contoh (3 kelas):</strong><br>
                                    • Classifier 1: Rendah vs (Sedang, Tinggi)<br>
                                    • Classifier 2: Sedang vs (Rendah, Tinggi)<br>
                                    • Classifier 3: Tinggi vs (Rendah, Sedang)
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="kernel-card">
                                <h6><i class="bi bi-arrows-angle-contract me-2"></i>One-vs-One (OvO)</h6>
                                <p class="text-muted small mb-2">
                                    Untuk K kelas, buat K(K-1)/2 classifier biner. Setiap classifier
                                    membedakan pasangan kelas.
                                </p>
                                <div class="bg-light p-2 rounded small">
                                    <strong>Contoh (3 kelas):</strong><br>
                                    • Classifier 1: Rendah vs Sedang<br>
                                    • Classifier 2: Rendah vs Tinggi<br>
                                    • Classifier 3: Sedang vs Tinggi
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="mt-4">Proses Prediksi Multi-class (OvR):</h6>
                    <ol>
                        <li>Hitung decision function untuk setiap classifier</li>
                        <li>Konversi ke probability menggunakan softmax</li>
                        <li>Pilih kelas dengan probability tertinggi</li>
                    </ol>
                </div>
            </section>

            <!-- 7. Parameter SVM -->
            <section id="parameter" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-sliders me-2"></i>7. Parameter SVM</h5>
                </div>
                <div class="card-body">
                    <p>
                        Pemilihan parameter yang tepat sangat penting untuk performa model SVM:
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered feature-table">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Parameter</th>
                                    <th style="width: 35%;">Deskripsi</th>
                                    <th style="width: 25%;">Pengaruh</th>
                                    <th style="width: 25%;">Nilai Umum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>C</strong></td>
                                    <td>
                                        Parameter regularisasi yang mengontrol trade-off antara
                                        margin maksimal dan kesalahan klasifikasi.
                                    </td>
                                    <td>
                                        <span class="text-success">C besar</span>: Margin kecil, fit training data ketat (risiko overfit)<br>
                                        <span class="text-danger">C kecil</span>: Margin besar, toleran error (risiko underfit)
                                    </td>
                                    <td>0.1, 1, 10, 100</td>
                                </tr>
                                <tr>
                                    <td><strong>γ (Gamma)</strong></td>
                                    <td>
                                        Parameter untuk kernel RBF, polynomial, dan sigmoid.
                                        Menentukan seberapa jauh pengaruh satu sample.
                                    </td>
                                    <td>
                                        <span class="text-success">γ besar</span>: Pengaruh lokal, decision boundary kompleks<br>
                                        <span class="text-danger">γ kecil</span>: Pengaruh global, decision boundary smooth
                                    </td>
                                    <td>1/n_features (auto), 0.001, 0.01, 0.1</td>
                                </tr>
                                <tr>
                                    <td><strong>Kernel</strong></td>
                                    <td>
                                        Fungsi yang memetakan data ke ruang dimensi lebih tinggi.
                                    </td>
                                    <td>
                                        Menentukan bentuk decision boundary dan kemampuan
                                        menangani non-linearitas.
                                    </td>
                                    <td>linear, rbf, poly, sigmoid</td>
                                </tr>
                                <tr>
                                    <td><strong>Degree</strong></td>
                                    <td>
                                        Derajat untuk polynomial kernel.
                                    </td>
                                    <td>
                                        Degree lebih tinggi = lebih fleksibel tapi risiko overfit.
                                    </td>
                                    <td>2, 3, 4, 5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="highlight-box">
                        <strong><i class="bi bi-lightbulb me-2"></i>Tips Tuning Parameter:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Mulai dengan RBF kernel (default) dan C=1, γ=auto</li>
                            <li>Gunakan cross-validation untuk evaluasi</li>
                            <li>Lakukan grid search untuk kombinasi optimal</li>
                            <li>Pertimbangkan linear kernel jika data berdimensi tinggi</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 8. Metrik Evaluasi -->
            <section id="evaluasi" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-graph-up me-2"></i>8. Metrik Evaluasi Model</h5>
                </div>
                <div class="card-body">
                    <p>
                        Untuk mengevaluasi performa model klasifikasi, digunakan beberapa metrik:
                    </p>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="kernel-card h-100">
                                <h6><i class="bi bi-bullseye text-primary me-2"></i>Accuracy</h6>
                                <div class="formula">
                                    Accuracy = (TP + TN) / (TP + TN + FP + FN)
                                </div>
                                <p class="text-muted small mt-2 mb-0">
                                    Proporsi prediksi yang benar dari seluruh prediksi.
                                    Cocok jika distribusi kelas seimbang.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="kernel-card h-100">
                                <h6><i class="bi bi-check2-square text-success me-2"></i>Precision</h6>
                                <div class="formula">
                                    Precision = TP / (TP + FP)
                                </div>
                                <p class="text-muted small mt-2 mb-0">
                                    Dari semua prediksi positif, berapa yang benar-benar positif.
                                    Penting jika false positive costly.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="kernel-card h-100">
                                <h6><i class="bi bi-search text-warning me-2"></i>Recall (Sensitivity)</h6>
                                <div class="formula">
                                    Recall = TP / (TP + FN)
                                </div>
                                <p class="text-muted small mt-2 mb-0">
                                    Dari semua yang benar-benar positif, berapa yang terdeteksi.
                                    Penting jika false negative costly.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="kernel-card h-100">
                                <h6><i class="bi bi-bar-chart text-danger me-2"></i>F1-Score</h6>
                                <div class="formula">
                                    F1 = 2 × (Precision × Recall) / (Precision + Recall)
                                </div>
                                <p class="text-muted small mt-2 mb-0">
                                    Harmonic mean dari precision dan recall.
                                    Berguna untuk kelas yang tidak seimbang.
                                </p>
                            </div>
                        </div>
                    </div>

                    <h6 class="mt-4"><i class="bi bi-grid-3x3 me-2"></i>Confusion Matrix</h6>
                    <p>
                        Matriks yang menunjukkan distribusi prediksi vs nilai aktual:
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center" style="max-width: 500px;">
                            <thead>
                                <tr>
                                    <th rowspan="2" colspan="2" class="align-middle">Confusion Matrix</th>
                                    <th colspan="3" class="bg-light">Prediksi</th>
                                </tr>
                                <tr>
                                    <th class="bg-success text-white">Rendah</th>
                                    <th class="bg-warning">Sedang</th>
                                    <th class="bg-danger text-white">Tinggi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th rowspan="3" class="align-middle bg-light" style="writing-mode: vertical-rl;">Aktual</th>
                                    <th class="bg-success text-white">Rendah</th>
                                    <td class="bg-success-subtle">TP</td>
                                    <td>FP</td>
                                    <td>FP</td>
                                </tr>
                                <tr>
                                    <th class="bg-warning">Sedang</th>
                                    <td>FN</td>
                                    <td class="bg-warning-subtle">TP</td>
                                    <td>FP</td>
                                </tr>
                                <tr>
                                    <th class="bg-danger text-white">Tinggi</th>
                                    <td>FN</td>
                                    <td>FN</td>
                                    <td class="bg-danger-subtle">TP</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="mt-4"><i class="bi bi-arrow-repeat me-2"></i>Cross-Validation</h6>
                    <p>
                        Teknik untuk mengevaluasi model dengan membagi data menjadi K fold.
                        Sistem ini menggunakan <strong>5-Fold Cross-Validation</strong>:
                    </p>
                    <ol>
                        <li>Bagi data menjadi 5 bagian sama besar</li>
                        <li>Untuk setiap fold: gunakan 4 bagian untuk training, 1 untuk testing</li>
                        <li>Ulangi 5 kali dengan fold testing berbeda</li>
                        <li>Hitung rata-rata dan standar deviasi dari 5 skor</li>
                    </ol>
                </div>
            </section>

            <!-- 9. Implementasi -->
            <section id="implementasi" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-code-slash me-2"></i>9. Implementasi dalam Sistem</h5>
                </div>
                <div class="card-body">
                    <h6>Variabel Input (Fitur)</h6>
                    <p>
                        Sistem menggunakan 6 variabel aktivitas belajar sebagai input untuk prediksi:
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered feature-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Variabel</th>
                                    <th>Simbol</th>
                                    <th>Range</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Tingkat Kehadiran</td>
                                    <td>X₁</td>
                                    <td>0-100%</td>
                                    <td>Persentase kehadiran siswa di kelas</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Durasi Belajar</td>
                                    <td>X₂</td>
                                    <td>0-8 jam</td>
                                    <td>Rata-rata jam belajar per hari</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Frekuensi Tugas</td>
                                    <td>X₃</td>
                                    <td>0-50</td>
                                    <td>Jumlah tugas yang dikerjakan</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Partisipasi Diskusi</td>
                                    <td>X₄</td>
                                    <td>0-100%</td>
                                    <td>Tingkat keaktifan dalam diskusi</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Penggunaan Media</td>
                                    <td>X₅</td>
                                    <td>0-100%</td>
                                    <td>Pemanfaatan media pembelajaran</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Skor Kedisiplinan</td>
                                    <td>X₆</td>
                                    <td>0-100</td>
                                    <td>Nilai kedisiplinan siswa</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h6 class="mt-4">Variabel Output (Label)</h6>
                    <p>
                        Klasifikasi prestasi akademik menjadi 3 kategori:
                    </p>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-danger-subtle rounded">
                                <h3 class="text-danger mb-1">Rendah</h3>
                                <p class="mb-0">Nilai &lt; 60</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-warning-subtle rounded">
                                <h3 class="text-warning mb-1">Sedang</h3>
                                <p class="mb-0">Nilai 60 - 79</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-success-subtle rounded">
                                <h3 class="text-success mb-1">Tinggi</h3>
                                <p class="mb-0">Nilai ≥ 80</p>
                            </div>
                        </div>
                    </div>

                    <h6 class="mt-4">Alur Proses Sistem</h6>

                    <div class="row g-2 mt-2">
                        <div class="col">
                            <div class="flowchart-step">
                                <i class="bi bi-database text-primary" style="font-size: 1.5rem;"></i>
                                <div class="mt-2 small fw-bold">Pengumpulan Data</div>
                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <i class="bi bi-arrow-right text-primary"></i>
                        </div>
                        <div class="col">
                            <div class="flowchart-step">
                                <i class="bi bi-funnel text-primary" style="font-size: 1.5rem;"></i>
                                <div class="mt-2 small fw-bold">Preprocessing</div>
                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <i class="bi bi-arrow-right text-primary"></i>
                        </div>
                        <div class="col">
                            <div class="flowchart-step">
                                <i class="bi bi-pie-chart text-primary" style="font-size: 1.5rem;"></i>
                                <div class="mt-2 small fw-bold">Split Data</div>
                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <i class="bi bi-arrow-right text-primary"></i>
                        </div>
                        <div class="col">
                            <div class="flowchart-step active-step">
                                <i class="bi bi-cpu text-primary" style="font-size: 1.5rem;"></i>
                                <div class="mt-2 small fw-bold">Training SVM</div>
                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <i class="bi bi-arrow-right text-primary"></i>
                        </div>
                        <div class="col">
                            <div class="flowchart-step">
                                <i class="bi bi-clipboard-check text-primary" style="font-size: 1.5rem;"></i>
                                <div class="mt-2 small fw-bold">Evaluasi</div>
                            </div>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <i class="bi bi-arrow-right text-primary"></i>
                        </div>
                        <div class="col">
                            <div class="flowchart-step">
                                <i class="bi bi-lightning text-primary" style="font-size: 1.5rem;"></i>
                                <div class="mt-2 small fw-bold">Prediksi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 10. Kelebihan & Kekurangan -->
            <section id="kelebihan" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-list-check me-2"></i>10. Kelebihan dan Kekurangan SVM</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success"><i class="bi bi-hand-thumbs-up me-2"></i>Kelebihan</h6>

                            <div class="advantage-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <div>
                                    <strong>Efektif di dimensi tinggi</strong><br>
                                    <small class="text-muted">Bekerja baik meskipun jumlah fitur lebih banyak dari sample</small>
                                </div>
                            </div>

                            <div class="advantage-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <div>
                                    <strong>Memory efficient</strong><br>
                                    <small class="text-muted">Hanya menggunakan support vectors dalam decision function</small>
                                </div>
                            </div>

                            <div class="advantage-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <div>
                                    <strong>Fleksibel</strong><br>
                                    <small class="text-muted">Berbagai kernel tersedia untuk berbagai jenis data</small>
                                </div>
                            </div>

                            <div class="advantage-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <div>
                                    <strong>Robust terhadap outlier</strong><br>
                                    <small class="text-muted">Margin maksimal membuat model tidak sensitif terhadap outlier</small>
                                </div>
                            </div>

                            <div class="advantage-item">
                                <i class="bi bi-check-circle-fill"></i>
                                <div>
                                    <strong>Generalisasi baik</strong><br>
                                    <small class="text-muted">Prinsip margin maksimal mencegah overfitting</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-danger"><i class="bi bi-hand-thumbs-down me-2"></i>Kekurangan</h6>

                            <div class="advantage-item disadvantage-item">
                                <i class="bi bi-x-circle-fill"></i>
                                <div>
                                    <strong>Tidak scalable untuk data besar</strong><br>
                                    <small class="text-muted">Kompleksitas O(n²) hingga O(n³) untuk training</small>
                                </div>
                            </div>

                            <div class="advantage-item disadvantage-item">
                                <i class="bi bi-x-circle-fill"></i>
                                <div>
                                    <strong>Sensitif terhadap pemilihan parameter</strong><br>
                                    <small class="text-muted">Perlu tuning C, gamma, kernel yang tepat</small>
                                </div>
                            </div>

                            <div class="advantage-item disadvantage-item">
                                <i class="bi bi-x-circle-fill"></i>
                                <div>
                                    <strong>Tidak memberikan probability langsung</strong><br>
                                    <small class="text-muted">Perlu kalibrasi tambahan untuk estimasi probability</small>
                                </div>
                            </div>

                            <div class="advantage-item disadvantage-item">
                                <i class="bi bi-x-circle-fill"></i>
                                <div>
                                    <strong>Kurang interpretable</strong><br>
                                    <small class="text-muted">Sulit menjelaskan decision boundary terutama dengan kernel non-linear</small>
                                </div>
                            </div>

                            <div class="advantage-item disadvantage-item">
                                <i class="bi bi-x-circle-fill"></i>
                                <div>
                                    <strong>Sensitif terhadap noise</strong><br>
                                    <small class="text-muted">Fitur yang noisy dapat mempengaruhi kualitas model</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 11. Referensi -->
            <section id="referensi" class="content-card">
                <div class="card-header">
                    <h5><i class="bi bi-book me-2"></i>11. Referensi</h5>
                </div>
                <div class="card-body">
                    <div class="reference-item">
                        <strong>[1]</strong> Vapnik, V. N. (1995). <em>The Nature of Statistical Learning Theory</em>.
                        Springer-Verlag, New York.
                    </div>

                    <div class="reference-item">
                        <strong>[2]</strong> Cortes, C., & Vapnik, V. (1995). Support-vector networks.
                        <em>Machine Learning</em>, 20(3), 273-297.
                    </div>

                    <div class="reference-item">
                        <strong>[3]</strong> Platt, J. C. (1998). Sequential Minimal Optimization: A Fast Algorithm
                        for Training Support Vector Machines. <em>Microsoft Research Technical Report MSR-TR-98-14</em>.
                    </div>

                    <div class="reference-item">
                        <strong>[4]</strong> Burges, C. J. (1998). A tutorial on support vector machines for pattern
                        recognition. <em>Data Mining and Knowledge Discovery</em>, 2(2), 121-167.
                    </div>

                    <div class="reference-item">
                        <strong>[5]</strong> Hsu, C. W., Chang, C. C., & Lin, C. J. (2003). A practical guide to
                        support vector classification. <em>National Taiwan University</em>.
                    </div>

                    <div class="reference-item">
                        <strong>[6]</strong> Bishop, C. M. (2006). <em>Pattern Recognition and Machine Learning</em>.
                        Springer, New York.
                    </div>

                    <div class="reference-item">
                        <strong>[7]</strong> Hastie, T., Tibshirani, R., & Friedman, J. (2009).
                        <em>The Elements of Statistical Learning: Data Mining, Inference, and Prediction</em> (2nd ed.).
                        Springer, New York.
                    </div>
                </div>
            </section>

            <!-- Action Buttons -->
            <div class="d-flex gap-3 justify-content-center my-4 no-print">
                <a href="{{ route('admin.ml-models.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-list-ul me-2"></i>Lihat Model
                </a>
                <a href="{{ route('admin.ml-models.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-play-circle me-2"></i>Training Model Baru
                </a>
                <a href="{{ route('admin.predictions.create') }}" class="btn btn-success btn-lg">
                    <i class="bi bi-lightning me-2"></i>Buat Prediksi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Smooth scroll for table of contents
    document.querySelectorAll('.toc-sidebar a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    // Highlight active section in TOC
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('section[id]');
        const tocLinks = document.querySelectorAll('.toc-sidebar a');

        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (pageYOffset >= sectionTop - 100) {
                current = section.getAttribute('id');
            }
        });

        tocLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
</script>
@endsection
