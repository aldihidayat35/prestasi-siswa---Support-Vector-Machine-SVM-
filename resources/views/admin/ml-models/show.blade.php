@extends('layouts.app')

@section('title', 'Detail Model - ' . $model->name)
@section('header-title', 'Detail Model SVM')

@section('content')
<div class="row">
    <!-- Model Info -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $model->name }}</h5>
                @if($model->is_active)
                    <span class="badge bg-success">AKTIF</span>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Kernel</td>
                        <td class="text-end"><span class="badge bg-info text-uppercase">{{ $model->kernel }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Parameter C</td>
                        <td class="text-end fw-medium">{{ $model->c_param }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Gamma</td>
                        <td class="text-end">{{ $model->gamma ?? 'scale' }}</td>
                    </tr>
                    @if($model->kernel == 'poly')
                    <tr>
                        <td class="text-muted">Degree</td>
                        <td class="text-end">{{ $model->degree }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Training Date</td>
                        <td class="text-end">{{ $model->trained_at?->format('d M Y H:i') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Model Path</td>
                        <td class="text-end"><small>{{ $model->model_path ?? '-' }}</small></td>
                    </tr>
                </table>

                @if($model->description)
                <hr>
                <p class="text-muted small mb-0">{{ $model->description }}</p>
                @endif
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2">
                    @if(!$model->is_active)
                    <form action="{{ route('admin.ml-models.set-active', $model) }}" method="POST" class="flex-fill">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-lg me-1"></i>Aktifkan
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('admin.ml-models.retrain', $model) }}" method="POST" class="flex-fill">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-arrow-repeat me-1"></i>Re-train
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Metrik Performa</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <div class="fs-3 fw-bold text-primary">{{ $model->accuracy ? number_format($model->accuracy * 100, 1) . '%' : '-' }}</div>
                            <div class="text-muted small">Akurasi</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-3">
                            <div class="fs-3 fw-bold text-success">{{ $model->precision ? number_format($model->precision * 100, 1) . '%' : '-' }}</div>
                            <div class="text-muted small">Precision</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <div class="fs-3 fw-bold text-warning">{{ $model->recall ? number_format($model->recall * 100, 1) . '%' : '-' }}</div>
                            <div class="text-muted small">Recall</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-3">
                            <div class="fs-3 fw-bold text-danger">{{ $model->f1_score ? number_format($model->f1_score * 100, 1) . '%' : '-' }}</div>
                            <div class="text-muted small">F1-Score</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluation Results -->
    <div class="col-lg-8">
        <!-- Performance Chart -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Visualisasi Performa</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="metricsChart" height="250"></canvas>
                    </div>
                    <div class="col-md-6">
                        <canvas id="radarChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Confusion Matrix -->
        @if($evaluation && isset($evaluation->confusion_matrix))
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Confusion Matrix</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @php
                        $matrix = $evaluation->confusion_matrix;
                        $labels = ['Rendah', 'Sedang', 'Tinggi'];
                    @endphp
                    <table class="table table-bordered text-center">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" colspan="2" class="align-middle">Confusion Matrix</th>
                                <th colspan="3">Prediksi</th>
                            </tr>
                            <tr>
                                @foreach($labels as $label)
                                <th>{{ $label }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($labels as $i => $actualLabel)
                            <tr>
                                @if($i == 0)
                                <th rowspan="3" class="align-middle table-light" style="writing-mode: vertical-lr;">Aktual</th>
                                @endif
                                <th class="table-light">{{ $actualLabel }}</th>
                                @foreach($labels as $j => $predLabel)
                                    @php
                                        $value = $matrix[$i][$j] ?? 0;
                                        $isCorrect = $i == $j;
                                    @endphp
                                    <td class="{{ $isCorrect ? 'table-success' : '' }}">
                                        <strong>{{ $value }}</strong>
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Diagonal hijau menunjukkan prediksi yang benar. Nilai di luar diagonal menunjukkan kesalahan klasifikasi.
                </p>
            </div>
        </div>
        @endif

        <!-- Cross Validation -->
        @if($evaluation && isset($evaluation->cv_scores))
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Cross-Validation (5-Fold)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Fold</th>
                                @foreach($evaluation->cv_scores as $index => $score)
                                <th class="text-center">Fold {{ $index + 1 }}</th>
                                @endforeach
                                <th class="text-center">Mean</th>
                                <th class="text-center">Std</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Akurasi</td>
                                @foreach($evaluation->cv_scores as $score)
                                <td class="text-center">{{ number_format($score * 100, 2) }}%</td>
                                @endforeach
                                <td class="text-center fw-bold">{{ number_format(array_sum($evaluation->cv_scores) / count($evaluation->cv_scores) * 100, 2) }}%</td>
                                <td class="text-center">
                                    @php
                                        $mean = array_sum($evaluation->cv_scores) / count($evaluation->cv_scores);
                                        $variance = array_sum(array_map(fn($x) => pow($x - $mean, 2), $evaluation->cv_scores)) / count($evaluation->cv_scores);
                                        $std = sqrt($variance);
                                    @endphp
                                    ± {{ number_format($std * 100, 2) }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <canvas id="cvChart" height="150"></canvas>
            </div>
        </div>
        @endif

        <!-- Classification Report -->
        @if($evaluation && isset($evaluation->classification_report))
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Classification Report</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Label</th>
                                <th class="text-center">Precision</th>
                                <th class="text-center">Recall</th>
                                <th class="text-center">F1-Score</th>
                                <th class="text-center">Support</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluation->classification_report as $label => $metrics)
                                @if(!in_array($label, ['accuracy', 'macro avg', 'weighted avg']))
                                <tr>
                                    <td>
                                        @php
                                            $badgeClass = match($label) {
                                                'Tinggi' => 'bg-success',
                                                'Sedang' => 'bg-warning',
                                                'Rendah' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                                    </td>
                                    <td class="text-center">{{ number_format($metrics['precision'] * 100, 2) }}%</td>
                                    <td class="text-center">{{ number_format($metrics['recall'] * 100, 2) }}%</td>
                                    <td class="text-center">{{ number_format($metrics['f1-score'] * 100, 2) }}%</td>
                                    <td class="text-center">{{ $metrics['support'] }}</td>
                                </tr>
                                @endif
                            @endforeach
                            <tr class="table-secondary">
                                <td><strong>Weighted Avg</strong></td>
                                <td class="text-center fw-bold">{{ number_format($evaluation->classification_report['weighted avg']['precision'] * 100, 2) }}%</td>
                                <td class="text-center fw-bold">{{ number_format($evaluation->classification_report['weighted avg']['recall'] * 100, 2) }}%</td>
                                <td class="text-center fw-bold">{{ number_format($evaluation->classification_report['weighted avg']['f1-score'] * 100, 2) }}%</td>
                                <td class="text-center">{{ $evaluation->classification_report['weighted avg']['support'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Predictions using this model -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Prediksi Menggunakan Model Ini</h5>
                <span class="badge bg-primary">{{ $model->predictions->count() }} prediksi</span>
            </div>
            <div class="card-body p-0">
                @if($model->predictions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Siswa</th>
                                <th>Hasil</th>
                                <th>Confidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->predictions->take(5) as $prediction)
                            <tr>
                                <td>{{ $prediction->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $prediction->student?->name ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $predBadge = match($prediction->predicted_label) {
                                            'Tinggi' => 'bg-success',
                                            'Sedang' => 'bg-warning',
                                            'Rendah' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $predBadge }}">{{ $prediction->predicted_label }}</span>
                                </td>
                                <td>{{ number_format($prediction->confidence * 100, 1) }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-muted mb-0">Belum ada prediksi menggunakan model ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.ml-models.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Metrics Bar Chart
    const metricsCtx = document.getElementById('metricsChart').getContext('2d');
    new Chart(metricsCtx, {
        type: 'bar',
        data: {
            labels: ['Akurasi', 'Precision', 'Recall', 'F1-Score'],
            datasets: [{
                label: 'Performa (%)',
                data: [
                    {{ $model->accuracy ? $model->accuracy * 100 : 0 }},
                    {{ $model->precision ? $model->precision * 100 : 0 }},
                    {{ $model->recall ? $model->recall * 100 : 0 }},
                    {{ $model->f1_score ? $model->f1_score * 100 : 0 }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgb(54, 162, 235)',
                    'rgb(75, 192, 192)',
                    'rgb(255, 206, 86)',
                    'rgb(255, 99, 132)'
                ],
                borderWidth: 1
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

    // Radar Chart
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: ['Akurasi', 'Precision', 'Recall', 'F1-Score'],
            datasets: [{
                label: '{{ $model->name }}',
                data: [
                    {{ $model->accuracy ? $model->accuracy * 100 : 0 }},
                    {{ $model->precision ? $model->precision * 100 : 0 }},
                    {{ $model->recall ? $model->recall * 100 : 0 }},
                    {{ $model->f1_score ? $model->f1_score * 100 : 0 }}
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
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    @if($evaluation && isset($evaluation->cv_scores))
    // CV Scores Chart
    const cvCtx = document.getElementById('cvChart').getContext('2d');
    new Chart(cvCtx, {
        type: 'line',
        data: {
            labels: @json(array_map(fn($i) => 'Fold ' . ($i + 1), array_keys($evaluation->cv_scores))),
            datasets: [{
                label: 'CV Score',
                data: @json(array_map(fn($v) => $v * 100, $evaluation->cv_scores)),
                fill: true,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.3
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
                    beginAtZero: false,
                    min: Math.min(...@json(array_map(fn($v) => $v * 100, $evaluation->cv_scores))) - 5,
                    max: 100
                }
            }
        }
    });
    @endif
});
</script>
@endpush
