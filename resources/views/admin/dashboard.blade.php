@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('header-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_students'] }}</h3>
                <p>Total Siswa</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-journal-check"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_activities'] }}</h3>
                <p>Aktivitas Belajar</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-cpu"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_models'] }}</h3>
                <p>Model SVM</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total_predictions'] }}</h3>
                <p>Prediksi</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Model Performance Card -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Model SVM Aktif</h5>
                @if($stats['active_model'])
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-secondary">Belum Ada</span>
                @endif
            </div>
            <div class="card-body">
                @if($modelPerformance)
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-0">{{ number_format($modelPerformance['accuracy'] * 100, 2) }}%</h4>
                                <small class="text-muted">Accuracy</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-0">{{ number_format($modelPerformance['precision'] * 100, 2) }}%</h4>
                                <small class="text-muted">Precision</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning mb-0">{{ number_format($modelPerformance['recall'] * 100, 2) }}%</h4>
                                <small class="text-muted">Recall</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-0">{{ number_format($modelPerformance['f1_score'] * 100, 2) }}%</h4>
                                <small class="text-muted">F1-Score</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-1"><strong>Nama Model:</strong> {{ $stats['active_model']->name }}</p>
                        <p class="mb-1"><strong>Kernel:</strong> {{ ucfirst($stats['active_model']->kernel) }}</p>
                        <p class="mb-0"><strong>Dilatih:</strong> {{ $stats['active_model']->training_date?->format('d M Y H:i') ?? '-' }}</p>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-cpu text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Belum ada model SVM yang aktif.</p>
                        <a href="{{ route('admin.ml-models.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Training Model Baru
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Prediction Distribution Chart -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Distribusi Prediksi</h5>
            </div>
            <div class="card-body">
                <canvas id="predictionChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Category Distribution -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title">Distribusi Kategori Nilai</h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Predictions -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Prediksi Terbaru</h5>
                <a href="{{ route('admin.predictions.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($latestPredictions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>Prediksi</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestPredictions as $prediction)
                                    <tr>
                                        <td>{{ $prediction->student->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-{{ strtolower($prediction->predicted_label) }}">
                                                {{ $prediction->predicted_label }}
                                            </span>
                                        </td>
                                        <td>{{ $prediction->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">Belum ada prediksi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Prediction Distribution Chart
    const predictionCtx = document.getElementById('predictionChart').getContext('2d');
    new Chart(predictionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Rendah', 'Sedang', 'Tinggi'],
            datasets: [{
                data: [
                    {{ $predictionDistribution['Rendah'] ?? 0 }},
                    {{ $predictionDistribution['Sedang'] ?? 0 }},
                    {{ $predictionDistribution['Tinggi'] ?? 0 }}
                ],
                backgroundColor: ['#F64E60', '#FFA800', '#1BC5BD'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Category Distribution Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: ['Rendah', 'Sedang', 'Tinggi'],
            datasets: [{
                label: 'Jumlah Siswa',
                data: [
                    {{ $categoryDistribution['Rendah'] ?? 0 }},
                    {{ $categoryDistribution['Sedang'] ?? 0 }},
                    {{ $categoryDistribution['Tinggi'] ?? 0 }}
                ],
                backgroundColor: ['#F64E60', '#FFA800', '#1BC5BD'],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
