@extends('layouts.app')

@section('title', 'Daftar Prediksi')
@section('header-title', 'Riwayat Prediksi')

@section('content')
<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Total Prediksi</div>
                        <div class="fs-3 fw-bold text-primary">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                    <i class="bi bi-cpu fs-1 text-primary opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Prestasi Tinggi</div>
                        <div class="fs-3 fw-bold text-success">{{ $stats['tinggi'] ?? 0 }}</div>
                    </div>
                    <i class="bi bi-emoji-smile fs-1 text-success opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Prestasi Sedang</div>
                        <div class="fs-3 fw-bold text-warning">{{ $stats['sedang'] ?? 0 }}</div>
                    </div>
                    <i class="bi bi-emoji-neutral fs-1 text-warning opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-danger bg-opacity-10">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Prestasi Rendah</div>
                        <div class="fs-3 fw-bold text-danger">{{ $stats['rendah'] ?? 0 }}</div>
                    </div>
                    <i class="bi bi-emoji-frown fs-1 text-danger opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.predictions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Prediksi Baru
        </a>
        <a href="{{ route('admin.predictions.batch') }}" class="btn btn-outline-primary">
            <i class="bi bi-collection me-1"></i>Prediksi Batch
        </a>
    </div>

    <!-- Filters -->
    <form action="{{ route('admin.predictions.index') }}" method="GET" class="d-flex gap-2">
        <select name="result" class="form-select form-select-sm" style="width: 150px;">
            <option value="">Semua Hasil</option>
            <option value="Tinggi" {{ request('result') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
            <option value="Sedang" {{ request('result') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
            <option value="Rendah" {{ request('result') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
        </select>
        <select name="model_id" class="form-select form-select-sm" style="width: 180px;">
            <option value="">Semua Model</option>
            @foreach($models as $model)
            <option value="{{ $model->id }}" {{ request('model_id') == $model->id ? 'selected' : '' }}>
                {{ $model->name }}
            </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
    </form>
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
                        <th>Model</th>
                        <th class="text-center">Hasil</th>
                        <th class="text-center">Confidence</th>
                        <th>Diprediksi Oleh</th>
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
                            <a href="{{ route('admin.students.show', $prediction->student) }}" class="text-decoration-none">
                                {{ $prediction->student?->name ?? 'N/A' }}
                            </a>
                        </td>
                        <td>{{ $prediction->student?->class ?? '-' }}</td>
                        <td>
                            <small>{{ $prediction->mlModel?->name ?? 'N/A' }}</small>
                        </td>
                        <td class="text-center">
                            @php
                                $badgeClass = match($prediction->predicted_label) {
                                    'Tinggi' => 'bg-success',
                                    'Sedang' => 'bg-warning',
                                    'Rendah' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-3">{{ $prediction->predicted_label }}</span>
                        </td>
                        <td class="text-center">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $badgeClass }}" role="progressbar"
                                     style="width: {{ $prediction->confidence * 100 }}%">
                                    {{ number_format($prediction->confidence * 100, 1) }}%
                                </div>
                            </div>
                        </td>
                        <td>{{ $prediction->predictedBy?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.predictions.show', $prediction) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-cpu fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada prediksi</p>
                            <a href="{{ route('admin.predictions.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Buat Prediksi Pertama
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

<!-- Distribution Chart -->
@if($predictions->count() > 0)
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Distribusi Hasil Prediksi</h5>
            </div>
            <div class="card-body">
                <canvas id="distributionChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tren Prediksi Bulanan</h5>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if($predictions->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Distribution Chart
    new Chart(document.getElementById('distributionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Tinggi', 'Sedang', 'Rendah'],
            datasets: [{
                data: [{{ $stats['tinggi'] ?? 0 }}, {{ $stats['sedang'] ?? 0 }}, {{ $stats['rendah'] ?? 0 }}],
                backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Trend Chart (mock data - should be from controller)
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [
                {
                    label: 'Tinggi',
                    data: [5, 8, 12, 10, 15, 18],
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.3
                },
                {
                    label: 'Sedang',
                    data: [10, 12, 8, 15, 12, 10],
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    tension: 0.3
                },
                {
                    label: 'Rendah',
                    data: [8, 5, 3, 4, 2, 1],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endif
@endpush
