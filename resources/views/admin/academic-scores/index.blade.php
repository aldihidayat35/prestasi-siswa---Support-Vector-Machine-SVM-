@extends('layouts.app')

@section('title', 'Nilai Akademik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Nilai Akademik</h4>
        <p class="text-muted mb-0">Data nilai akademik siswa per semester</p>
    </div>
    <a href="{{ route('admin.academic-scores.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Nilai
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-journal-check"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $scores->total() }}</h3>
                <span class="text-muted">Total Data</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-emoji-smile"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $scores->where('average_score', '>=', 80)->count() }}</h3>
                <span class="text-muted">Tinggi (≥80)</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-emoji-neutral"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $scores->whereBetween('average_score', [60, 79.99])->count() }}</h3>
                <span class="text-muted">Sedang (60-79)</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                <i class="bi bi-emoji-frown"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $scores->where('average_score', '<', 60)->count() }}</h3>
                <span class="text-muted">Rendah (&lt;60)</span>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.academic-scores.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Cari Siswa</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Nama/NISN..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Kelas</label>
                <select name="class" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach(['X IPA 1', 'X IPA 2', 'XI IPA 1', 'XI IPA 2', 'XII IPA 1', 'XII IPA 2'] as $class)
                        <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>
                            {{ $class }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select">
                    <option value="">Semua</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>
                            Semester {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun Ajaran</label>
                <select name="academic_year" class="form-select">
                    <option value="">Semua</option>
                    @foreach(['2025/2026', '2024/2025', '2023/2024'] as $year)
                        <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('admin.academic-scores.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Daftar Nilai Akademik</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Semester</th>
                        <th>Tahun Ajaran</th>
                        <th class="text-center">Rata-rata</th>
                        <th class="text-center">Kategori</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scores as $index => $score)
                        <tr>
                            <td>{{ $scores->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $score->student->name ?? '-' }}</div>
                                <small class="text-muted">{{ $score->student->nisn ?? '' }}</small>
                            </td>
                            <td>{{ $score->student->class ?? '-' }}</td>
                            <td>Semester {{ $score->semester }}</td>
                            <td>{{ $score->academic_year }}</td>
                            <td class="text-center">
                                <strong>{{ number_format($score->average_score, 2) }}</strong>
                            </td>
                            <td class="text-center">
                                @php
                                    $avg = $score->average_score;
                                    if ($avg >= 80) {
                                        $badge = 'bg-success';
                                        $label = 'Tinggi';
                                    } elseif ($avg >= 60) {
                                        $badge = 'bg-warning';
                                        $label = 'Sedang';
                                    } else {
                                        $badge = 'bg-danger';
                                        $label = 'Rendah';
                                    }
                                @endphp
                                <span class="badge {{ $badge }}">{{ $label }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.academic-scores.show', $score) }}"
                                       class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.academic-scores.edit', $score) }}"
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.academic-scores.destroy', $score) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                Belum ada data nilai akademik
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($scores->hasPages())
        <div class="card-footer">
            {{ $scores->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
