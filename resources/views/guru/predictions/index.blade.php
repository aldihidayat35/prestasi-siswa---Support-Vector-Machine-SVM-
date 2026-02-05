@extends('layouts.app')

@section('title', 'Hasil Prediksi')
@section('header-title', 'Hasil Prediksi Prestasi Siswa')

@section('content')
<!-- Summary Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <div class="fs-3 fw-bold text-success">{{ $stats['tinggi'] ?? 0 }}</div>
                <div class="text-muted small">Prestasi Tinggi</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <div class="fs-3 fw-bold text-warning">{{ $stats['sedang'] ?? 0 }}</div>
                <div class="text-muted small">Prestasi Sedang</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <div class="fs-3 fw-bold text-danger">{{ $stats['rendah'] ?? 0 }}</div>
                <div class="text-muted small">Prestasi Rendah</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <div class="fs-3 fw-bold text-primary">{{ $stats['total'] ?? 0 }}</div>
                <div class="text-muted small">Total Prediksi</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Actions -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('guru.predictions.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Cari Siswa</label>
                <input type="text" name="search" class="form-control" placeholder="Nama siswa..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Kelas</label>
                <select name="class" class="form-select">
                    <option value="">Semua</option>
                    <option value="XI IPA 1" {{ request('class') == 'XI IPA 1' ? 'selected' : '' }}>XI IPA 1</option>
                    <option value="XI IPA 2" {{ request('class') == 'XI IPA 2' ? 'selected' : '' }}>XI IPA 2</option>
                    <option value="XI IPS 1" {{ request('class') == 'XI IPS 1' ? 'selected' : '' }}>XI IPS 1</option>
                    <option value="XI IPS 2" {{ request('class') == 'XI IPS 2' ? 'selected' : '' }}>XI IPS 2</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Hasil</label>
                <select name="result" class="form-select">
                    <option value="">Semua</option>
                    <option value="Tinggi" {{ request('result') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                    <option value="Sedang" {{ request('result') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Rendah" {{ request('result') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('guru.predictions.create') }}" class="btn btn-success">
                    <i class="bi bi-cpu me-1"></i>Prediksi Baru
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Predictions Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th class="text-center">Hasil Prediksi</th>
                        <th class="text-center">Confidence</th>
                        <th>Model</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($predictions as $prediction)
                    <tr>
                        <td>{{ $loop->iteration + ($predictions->currentPage() - 1) * $predictions->perPage() }}</td>
                        <td>
                            <div>{{ $prediction->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $prediction->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $prediction->student?->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $prediction->student?->nis ?? '' }}</small>
                        </td>
                        <td><span class="badge bg-info">{{ $prediction->student?->class ?? '-' }}</span></td>
                        <td class="text-center">
                            @php
                                $badgeClass = match($prediction->predicted_label) {
                                    'Tinggi' => 'bg-success',
                                    'Sedang' => 'bg-warning',
                                    'Rendah' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-3 py-2">{{ $prediction->predicted_label }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="progress flex-grow-1 me-2" style="height: 6px; max-width: 80px;">
                                    <div class="progress-bar {{ $badgeClass }}" style="width: {{ $prediction->confidence * 100 }}%"></div>
                                </div>
                                <span class="small">{{ number_format($prediction->confidence * 100, 0) }}%</span>
                            </div>
                        </td>
                        <td><small>{{ $prediction->mlModel?->name ?? 'N/A' }}</small></td>
                        <td>
                            <a href="{{ route('guru.predictions.show', $prediction) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-cpu fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada hasil prediksi</p>
                            <a href="{{ route('guru.predictions.create') }}" class="btn btn-success">
                                <i class="bi bi-cpu me-1"></i>Buat Prediksi Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($predictions->hasPages())
    <div class="card-footer">
        {{ $predictions->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
