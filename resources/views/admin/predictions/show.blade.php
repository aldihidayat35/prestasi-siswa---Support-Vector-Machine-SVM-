@extends('layouts.app')

@section('title', 'Detail Prediksi')
@section('header-title', 'Detail Hasil Prediksi')

@section('content')
<div class="row">
    <!-- Prediction Result -->
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
                    $iconClass = match($prediction->predicted_label) {
                        'Tinggi' => 'bi-emoji-smile',
                        'Sedang' => 'bi-emoji-neutral',
                        'Rendah' => 'bi-emoji-frown',
                        default => 'bi-question-circle'
                    };
                @endphp

                <div class="mb-3">
                    <span class="badge {{ $badgeClass }} fs-1 px-5 py-3">
                        {{ $prediction->predicted_label }}
                    </span>
                </div>

                <div class="mb-3">
                    <i class="bi {{ $iconClass }} fs-1 {{ str_replace('bg-', 'text-', $badgeClass) }}"></i>
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

                <table class="table table-sm table-borderless text-start">
                    <tr>
                        <td class="text-muted">ID Prediksi</td>
                        <td class="text-end">#{{ $prediction->id }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Waktu</td>
                        <td class="text-end">{{ $prediction->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Model</td>
                        <td class="text-end">{{ $prediction->mlModel?->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Oleh</td>
                        <td class="text-end">{{ $prediction->predictedBy?->name ?? 'System' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Recommendation -->
        @if($prediction->recommendation)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightbulb me-2"></i>Rekomendasi
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $prediction->recommendation }}</p>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="card">
            <div class="card-body">
                <a href="{{ route('admin.predictions.create') }}?student_id={{ $prediction->student_id }}" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-arrow-repeat me-1"></i>Prediksi Ulang
                </a>
                <a href="{{ route('admin.students.show', $prediction->student) }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-person me-1"></i>Lihat Profil Siswa
                </a>
            </div>
        </div>
    </div>

    <!-- Student Info & Features -->
    <div class="col-lg-8">
        <!-- Student Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Siswa</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-person fs-1 text-primary"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h4 class="mb-1">{{ $prediction->student?->name ?? 'N/A' }}</h4>
                        <p class="text-muted mb-0">{{ $prediction->student?->nis ?? '' }}</p>
                        <span class="badge bg-info">{{ $prediction->student?->class ?? '-' }}</span>
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
                @php
                    $features = $prediction->input_features;
                @endphp
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3 text-center">
                            <div class="fs-3 fw-bold text-primary">{{ $features['attendance_rate'] ?? 0 }}%</div>
                            <div class="text-muted small">Tingkat Kehadiran</div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-primary" style="width: {{ $features['attendance_rate'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3 text-center">
                            <div class="fs-3 fw-bold text-info">{{ $features['study_duration'] ?? 0 }}</div>
                            <div class="text-muted small">Jam Belajar/Hari</div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-info" style="width: {{ min(($features['study_duration'] ?? 0) / 8 * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3 text-center">
                            <div class="fs-3 fw-bold text-success">{{ $features['task_frequency'] ?? 0 }}</div>
                            <div class="text-muted small">Frekuensi Tugas</div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-success" style="width: {{ min(($features['task_frequency'] ?? 0) / 30 * 100, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3 text-center">
                            <div class="fs-3 fw-bold text-warning">{{ $features['discussion_participation'] ?? 0 }}</div>
                            <div class="text-muted small">Partisipasi Diskusi</div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-warning" style="width: {{ $features['discussion_participation'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3 text-center">
                            <div class="fs-3 fw-bold text-danger">{{ $features['media_usage'] ?? 0 }}</div>
                            <div class="text-muted small">Penggunaan Media</div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-danger" style="width: {{ $features['media_usage'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3 text-center">
                            <div class="fs-3 fw-bold text-secondary">{{ $features['discipline_score'] ?? 0 }}</div>
                            <div class="text-muted small">Skor Kedisiplinan</div>
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-secondary" style="width: {{ $features['discipline_score'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Radar Chart -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Visualisasi Aktivitas Belajar</h5>
            </div>
            <div class="card-body">
                <canvas id="featuresRadar" height="300"></canvas>
            </div>
        </div>

        <!-- Model Info -->
        @if($prediction->mlModel)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Model</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Nama Model</td>
                                <td class="text-end fw-medium">{{ $prediction->mlModel->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kernel</td>
                                <td class="text-end text-uppercase">{{ $prediction->mlModel->kernel }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Parameter C</td>
                                <td class="text-end">{{ $prediction->mlModel->c_param }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-muted">Akurasi</td>
                                <td class="text-end text-success fw-bold">{{ number_format($prediction->mlModel->accuracy * 100, 1) }}%</td>
                            </tr>
                            <tr>
                                <td class="text-muted">F1-Score</td>
                                <td class="text-end">{{ number_format($prediction->mlModel->f1_score * 100, 1) }}%</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Training Date</td>
                                <td class="text-end">{{ $prediction->mlModel->trained_at?->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.predictions.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const features = @json($prediction->input_features);

    new Chart(document.getElementById('featuresRadar'), {
        type: 'radar',
        data: {
            labels: [
                'Kehadiran',
                'Durasi Belajar',
                'Frek. Tugas',
                'Diskusi',
                'Media',
                'Disiplin'
            ],
            datasets: [{
                label: 'Nilai Aktivitas',
                data: [
                    features.attendance_rate || 0,
                    (features.study_duration || 0) / 8 * 100, // Normalize to 100
                    (features.task_frequency || 0) / 30 * 100, // Normalize to 100
                    features.discussion_participation || 0,
                    features.media_usage || 0,
                    features.discipline_score || 0
                ],
                fill: true,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgb(54, 162, 235)',
                pointBackgroundColor: 'rgb(54, 162, 235)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(54, 162, 235)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { display: true },
                    suggestedMin: 0,
                    suggestedMax: 100
                }
            },
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
