@extends('layouts.app')

@section('title', 'Detail Prediksi')
@section('header-title', 'Detail Hasil Prediksi')

@section('content')
<div class="row">
    <!-- Result Card -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center py-5">
                @php
                    $badgeClass = match($prediction->predicted_label) {
                        'Tinggi' => 'bg-success',
                        'Sedang' => 'bg-warning',
                        'Rendah' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    $textClass = str_replace('bg-', 'text-', $badgeClass);
                @endphp

                <div class="mb-3">
                    <span class="badge {{ $badgeClass }} fs-2 px-5 py-3">
                        {{ $prediction->predicted_label }}
                    </span>
                </div>

                <div class="mb-4">
                    <div class="text-muted small mb-1">Tingkat Keyakinan</div>
                    <div class="progress mx-auto" style="height: 25px; max-width: 200px;">
                        <div class="progress-bar {{ $badgeClass }}" role="progressbar"
                             style="width: {{ $prediction->confidence * 100 }}%">
                            <span class="fw-bold">{{ number_format($prediction->confidence * 100, 1) }}%</span>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-start">
                    <div class="row mb-2">
                        <div class="col text-muted">Waktu Prediksi</div>
                        <div class="col text-end">{{ $prediction->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col text-muted">Model</div>
                        <div class="col text-end">{{ $prediction->mlModel?->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row">
                        <div class="col text-muted">Diprediksi oleh</div>
                        <div class="col text-end">{{ $prediction->predictedBy?->name ?? 'System' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommendation -->
        @if($prediction->recommendation)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightbulb text-warning me-2"></i>Rekomendasi
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $prediction->recommendation }}</p>
            </div>
        </div>
        @endif

        <div class="d-grid">
            <a href="{{ route('guru.predictions.create') }}?student_id={{ $prediction->student_id }}" class="btn btn-primary mb-2">
                <i class="bi bi-arrow-repeat me-1"></i>Prediksi Ulang
            </a>
            <a href="{{ route('guru.predictions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Details -->
    <div class="col-lg-8">
        <!-- Student Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Siswa</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-person fs-1 text-primary"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h4 class="mb-1">{{ $prediction->student?->name ?? 'N/A' }}</h4>
                        <p class="text-muted mb-0">NIS: {{ $prediction->student?->nis ?? '-' }}</p>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-info fs-6">{{ $prediction->student?->class ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Features -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Data Aktivitas Belajar (Input)</h5>
            </div>
            <div class="card-body">
                @php $features = $prediction->input_features; @endphp
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center h-100">
                            <div class="fs-3 fw-bold text-primary">{{ $features['attendance_rate'] ?? 0 }}%</div>
                            <div class="text-muted small">Tingkat Kehadiran</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center h-100">
                            <div class="fs-3 fw-bold text-info">{{ $features['study_duration'] ?? 0 }}</div>
                            <div class="text-muted small">Jam Belajar/Hari</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center h-100">
                            <div class="fs-3 fw-bold text-success">{{ $features['task_frequency'] ?? 0 }}</div>
                            <div class="text-muted small">Frekuensi Tugas</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center h-100">
                            <div class="fs-3 fw-bold text-warning">{{ $features['discussion_participation'] ?? 0 }}</div>
                            <div class="text-muted small">Partisipasi Diskusi</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center h-100">
                            <div class="fs-3 fw-bold text-danger">{{ $features['media_usage'] ?? 0 }}</div>
                            <div class="text-muted small">Penggunaan Media</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center h-100">
                            <div class="fs-3 fw-bold text-secondary">{{ $features['discipline_score'] ?? 0 }}</div>
                            <div class="text-muted small">Skor Kedisiplinan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visualization -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Visualisasi Data</h5>
            </div>
            <div class="card-body">
                <canvas id="featuresChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const features = @json($prediction->input_features);

    new Chart(document.getElementById('featuresChart'), {
        type: 'bar',
        data: {
            labels: ['Kehadiran', 'Durasi Belajar', 'Frek. Tugas', 'Diskusi', 'Media', 'Disiplin'],
            datasets: [{
                label: 'Nilai',
                data: [
                    features.attendance_rate || 0,
                    (features.study_duration || 0) * 12.5, // Scale to 100
                    (features.task_frequency || 0) * 3.33, // Scale to 100
                    features.discussion_participation || 0,
                    features.media_usage || 0,
                    features.discipline_score || 0
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(255, 193, 7, 0.7)',
                    'rgba(220, 53, 69, 0.7)',
                    'rgba(108, 117, 125, 0.7)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});
</script>
@endpush
