@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('header-title', 'Dashboard Guru')

@section('content')
<!-- Welcome Card -->
<div class="card bg-primary bg-opacity-10 border-0 mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-person-circle fs-2 text-white"></i>
                </div>
            </div>
            <div class="col">
                <h4 class="mb-1">Selamat Datang, {{ auth()->user()->name }}!</h4>
                <p class="text-muted mb-0">Sistem Prediksi Prestasi Akademik Siswa - SMA Negeri 2 Bukittinggi</p>
            </div>
            <div class="col-auto">
                <span class="badge bg-info fs-6">{{ now()->format('l, d F Y') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Total Siswa</div>
                        <div class="fs-3 fw-bold">{{ $stats['totalStudents'] ?? 0 }}</div>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-people fs-4 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Data Aktivitas</div>
                        <div class="fs-3 fw-bold">{{ $stats['totalActivities'] ?? 0 }}</div>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-journal-text fs-4 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Aktivitas Saya</div>
                        <div class="fs-3 fw-bold">{{ $stats['myActivities'] ?? 0 }}</div>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-clipboard-check fs-4 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Prediksi Hari Ini</div>
                        <div class="fs-3 fw-bold">{{ $stats['todayPredictions'] ?? 0 }}</div>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-cpu fs-4 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('guru.learning-activities.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Input Aktivitas Belajar
                    </a>
                    <a href="{{ route('guru.predictions.create') }}" class="btn btn-success">
                        <i class="bi bi-cpu me-2"></i>Prediksi Prestasi Siswa
                    </a>
                    <a href="{{ route('guru.learning-activities.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-journal-text me-2"></i>Lihat Data Aktivitas
                    </a>
                    <a href="{{ route('guru.predictions.index') }}" class="btn btn-outline-success">
                        <i class="bi bi-graph-up me-2"></i>Lihat Hasil Prediksi
                    </a>
                </div>
            </div>
        </div>

        <!-- Students by Class -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Distribusi Siswa per Kelas</h5>
            </div>
            <div class="card-body">
                <canvas id="classChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Aktivitas Terbaru yang Saya Input</h5>
                <a href="{{ route('guru.learning-activities.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Periode</th>
                                <th class="text-center">Kehadiran</th>
                                <th class="text-center">Disiplin</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities ?? [] as $activity)
                            <tr>
                                <td>{{ $activity->student?->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-info">{{ $activity->student?->class ?? '-' }}</span></td>
                                <td>{{ $activity->period }}</td>
                                <td class="text-center">{{ $activity->attendance_rate }}%</td>
                                <td class="text-center">{{ $activity->discipline_score }}</td>
                                <td>{{ $activity->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada data aktivitas
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Predictions -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Prediksi Terbaru</h5>
                <a href="{{ route('guru.predictions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Hasil</th>
                                <th>Confidence</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPredictions ?? [] as $prediction)
                            <tr>
                                <td>{{ $prediction->student?->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-info">{{ $prediction->student?->class ?? '-' }}</span></td>
                                <td>
                                    @php
                                        $badgeClass = match($prediction->predicted_label) {
                                            'Tinggi' => 'bg-success',
                                            'Sedang' => 'bg-warning',
                                            'Rendah' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $prediction->predicted_label }}</span>
                                </td>
                                <td>{{ number_format($prediction->confidence * 100, 1) }}%</td>
                                <td>{{ $prediction->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Belum ada prediksi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('classChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($stats['byClass'] ?? [])),
            datasets: [{
                data: @json(array_values($stats['byClass'] ?? [])),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
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
});
</script>
@endpush
