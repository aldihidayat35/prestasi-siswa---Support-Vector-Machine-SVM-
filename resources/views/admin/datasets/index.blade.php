@extends('layouts.app')

@section('title', 'Manajemen Dataset')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Manajemen Dataset</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Dataset</li>
            </ol>
        </nav>
    </div>
</div>

{{-- Alert Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Statistics Cards --}}
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3 class="mb-1">{{ $stats['total'] }}</h3>
                <small>Total Dataset</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3 class="mb-1">{{ $stats['training'] }}</h3>
                <small>Training Set</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3 class="mb-1">{{ $stats['testing'] }}</h3>
                <small>Testing Set</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3 class="mb-1">{{ $stats['label_rendah'] }}</h3>
                <small>Label Rendah</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h3 class="mb-1">{{ $stats['label_sedang'] }}</h3>
                <small>Label Sedang</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3 class="mb-1">{{ $stats['label_tinggi'] }}</h3>
                <small>Label Tinggi</small>
            </div>
        </div>
    </div>
</div>

{{-- Action Buttons --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="bi bi-gear me-2"></i>Aksi Dataset</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            {{-- Generate Dataset --}}
            <div class="col-md-4">
                <div class="card bg-light h-100">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Generate Dataset</h6>
                        <p class="small text-muted mb-3">Buat dataset baru dari data aktivitas belajar dan nilai akademik siswa.</p>
                        <form action="{{ route('admin.datasets.generate') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-arrow-clockwise me-2"></i>Generate Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Split Dataset --}}
            <div class="col-md-4">
                <div class="card bg-light h-100">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-diagram-3 me-2 text-info"></i>Split Dataset</h6>
                        <p class="small text-muted mb-3">Bagi dataset menjadi training set dan testing set.</p>
                        <form action="{{ route('admin.datasets.split') }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <select name="train_ratio" class="form-select form-select-sm" style="width: auto;">
                                <option value="0.7">70% Training</option>
                                <option value="0.8" selected>80% Training</option>
                                <option value="0.9">90% Training</option>
                            </select>
                            <button type="submit" class="btn btn-info btn-sm text-white">
                                <i class="bi bi-scissors me-2"></i>Split
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Clear Dataset --}}
            <div class="col-md-4">
                <div class="card bg-light h-100">
                    <div class="card-body">
                        <h6 class="fw-bold"><i class="bi bi-trash me-2 text-danger"></i>Hapus Semua Dataset</h6>
                        <p class="small text-muted mb-3">Hapus semua data dataset. Aksi ini tidak dapat dibatalkan!</p>
                        <form action="{{ route('admin.datasets.clear') }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus semua dataset? Aksi ini tidak dapat dibatalkan!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" {{ $stats['total'] == 0 ? 'disabled' : '' }}>
                                <i class="bi bi-trash me-2"></i>Hapus Semua
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter and Search --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.datasets.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Cari Siswa</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Nama atau NISN..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tipe</label>
                <select name="type" class="form-select">
                    <option value="">Semua</option>
                    <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>Training</option>
                    <option value="testing" {{ request('type') == 'testing' ? 'selected' : '' }}>Testing</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Label</label>
                <select name="label" class="form-select">
                    <option value="">Semua</option>
                    <option value="Rendah" {{ request('label') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                    <option value="Sedang" {{ request('label') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Tinggi" {{ request('label') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.datasets.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Dataset Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="bi bi-database me-2"></i>Data Training & Testing</h5>
        <span class="badge bg-secondary">{{ $datasets->total() }} data</span>
    </div>
    <div class="card-body p-0">
        @if($datasets->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Siswa</th>
                            <th>Tipe</th>
                            <th>Features</th>
                            <th>Label</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datasets as $dataset)
                            <tr>
                                <td>{{ $loop->iteration + ($datasets->currentPage() - 1) * $datasets->perPage() }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $dataset->student->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $dataset->student->nisn ?? '-' }}</small>
                                </td>
                                <td>
                                    @if($dataset->is_training)
                                        <span class="badge bg-success">Training</span>
                                    @else
                                        <span class="badge bg-info">Testing</span>
                                    @endif
                                </td>
                                <td>
                                    <small>
                                        @if($dataset->features)
                                            <span class="badge bg-light text-dark me-1" title="Kehadiran">
                                                <i class="bi bi-calendar-check"></i> {{ $dataset->features['attendance_rate'] ?? '-' }}%
                                            </span>
                                            <span class="badge bg-light text-dark me-1" title="Durasi Belajar">
                                                <i class="bi bi-clock"></i> {{ $dataset->features['study_duration'] ?? '-' }}j
                                            </span>
                                            <span class="badge bg-light text-dark me-1" title="Frekuensi Tugas">
                                                <i class="bi bi-journal-check"></i> {{ $dataset->features['task_frequency'] ?? '-' }}
                                            </span>
                                            <span class="badge bg-light text-dark me-1" title="Partisipasi Diskusi">
                                                <i class="bi bi-chat-dots"></i> {{ $dataset->features['discussion_participation'] ?? '-' }}%
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    @if($dataset->label == 'Tinggi')
                                        <span class="badge bg-success">Tinggi</span>
                                    @elseif($dataset->label == 'Sedang')
                                        <span class="badge bg-warning text-dark">Sedang</span>
                                    @else
                                        <span class="badge bg-danger">Rendah</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $dataset->created_at->format('d/m/Y') }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="card-footer">
                {{ $datasets->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-database-x display-4 text-muted"></i>
                <p class="text-muted mt-3">Belum ada dataset.</p>
                <p class="small text-muted">Klik tombol "Generate Sekarang" di atas untuk membuat dataset dari data yang ada.</p>
            </div>
        @endif
    </div>
</div>

{{-- Info Card --}}
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Tentang Dataset</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold">Features (Input)</h6>
                <ul class="small mb-0">
                    <li><strong>Attendance Rate:</strong> Tingkat kehadiran siswa (%)</li>
                    <li><strong>Study Duration:</strong> Durasi belajar per hari (jam)</li>
                    <li><strong>Task Frequency:</strong> Frekuensi pengerjaan tugas</li>
                    <li><strong>Discussion Participation:</strong> Partisipasi dalam diskusi (%)</li>
                    <li><strong>Media Usage:</strong> Penggunaan media pembelajaran (%)</li>
                    <li><strong>Discipline Score:</strong> Skor kedisiplinan (0-100)</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold">Label (Output)</h6>
                <ul class="small mb-0">
                    <li><span class="badge bg-danger">Rendah</span> Nilai rata-rata &lt; 60</li>
                    <li><span class="badge bg-warning text-dark">Sedang</span> Nilai rata-rata 60 - 79</li>
                    <li><span class="badge bg-success">Tinggi</span> Nilai rata-rata &ge; 80</li>
                </ul>

                <h6 class="fw-bold mt-3">Training vs Testing</h6>
                <ul class="small mb-0">
                    <li><strong>Training Set:</strong> Digunakan untuk melatih model SVM</li>
                    <li><strong>Testing Set:</strong> Digunakan untuk evaluasi akurasi model</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
