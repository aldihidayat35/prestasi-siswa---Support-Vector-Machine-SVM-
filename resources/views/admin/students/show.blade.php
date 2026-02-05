@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('header-title', 'Detail Siswa')

@section('content')
<div class="row">
    <!-- Student Info Card -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="avatar-xl mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                    <i class="bi bi-person fs-1 text-primary"></i>
                </div>
                <h4 class="mb-1">{{ $student->name }}</h4>
                <p class="text-muted mb-0">{{ $student->nis }}</p>
                <span class="badge bg-info mt-2">{{ $student->class }}</span>
                @if($student->is_active)
                    <span class="badge bg-success mt-2">Aktif</span>
                @else
                    <span class="badge bg-secondary mt-2">Tidak Aktif</span>
                @endif
            </div>
            <div class="card-body border-top">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td><i class="bi bi-gender-ambiguous text-muted me-2"></i>Jenis Kelamin</td>
                        <td class="text-end fw-medium">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    @if($student->birth_place || $student->birth_date)
                    <tr>
                        <td><i class="bi bi-calendar3 text-muted me-2"></i>TTL</td>
                        <td class="text-end">
                            {{ $student->birth_place }}{{ $student->birth_place && $student->birth_date ? ', ' : '' }}
                            {{ $student->birth_date?->format('d M Y') }}
                        </td>
                    </tr>
                    @endif
                    @if($student->address)
                    <tr>
                        <td><i class="bi bi-geo-alt text-muted me-2"></i>Alamat</td>
                        <td class="text-end">{{ Str::limit($student->address, 30) }}</td>
                    </tr>
                    @endif
                    @if($student->parent_name)
                    <tr>
                        <td><i class="bi bi-people text-muted me-2"></i>Orang Tua</td>
                        <td class="text-end">{{ $student->parent_name }}</td>
                    </tr>
                    @endif
                    @if($student->parent_phone)
                    <tr>
                        <td><i class="bi bi-telephone text-muted me-2"></i>Telp Ortu</td>
                        <td class="text-end">{{ $student->parent_phone }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning btn-sm flex-fill">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.predictions.create') }}?student_id={{ $student->id }}" class="btn btn-primary btn-sm flex-fill">
                        <i class="bi bi-cpu me-1"></i>Prediksi
                    </a>
                </div>
            </div>
        </div>

        <!-- Latest Prediction -->
        @if($latestPrediction = $student->predictions->first())
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Prediksi Terakhir</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    @php
                        $badgeClass = match($latestPrediction->predicted_label) {
                            'Tinggi' => 'bg-success',
                            'Sedang' => 'bg-warning',
                            'Rendah' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }} fs-5 px-4 py-2">{{ $latestPrediction->predicted_label }}</span>
                    <p class="text-muted mt-2 mb-0">
                        Confidence: {{ number_format($latestPrediction->confidence * 100, 1) }}%
                    </p>
                </div>
                <p class="small text-muted mb-1">
                    <i class="bi bi-calendar3 me-1"></i>{{ $latestPrediction->created_at->format('d M Y H:i') }}
                </p>
                @if($latestPrediction->recommendation)
                <p class="small mb-0">
                    <strong>Rekomendasi:</strong> {{ Str::limit($latestPrediction->recommendation, 100) }}
                </p>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Activity Data -->
    <div class="col-lg-8">
        <!-- Learning Activities -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Data Aktivitas Belajar</h5>
                <a href="{{ route('admin.learning-activities.create') }}?student_id={{ $student->id }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus me-1"></i>Tambah
                </a>
            </div>
            <div class="card-body p-0">
                @if($student->learningActivities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Periode</th>
                                <th class="text-center">Kehadiran</th>
                                <th class="text-center">Durasi Belajar</th>
                                <th class="text-center">Frek. Tugas</th>
                                <th class="text-center">Diskusi</th>
                                <th class="text-center">Media</th>
                                <th class="text-center">Disiplin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->learningActivities->take(5) as $activity)
                            <tr>
                                <td>
                                    <span class="fw-medium">{{ $activity->period }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">{{ $activity->attendance_rate }}%</span>
                                </td>
                                <td class="text-center">{{ $activity->study_duration }} jam</td>
                                <td class="text-center">{{ $activity->task_frequency }}x</td>
                                <td class="text-center">{{ $activity->discussion_participation }}</td>
                                <td class="text-center">{{ $activity->media_usage }}</td>
                                <td class="text-center">{{ $activity->discipline_score }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($student->learningActivities->count() > 5)
                <div class="card-footer text-center">
                    <a href="{{ route('admin.learning-activities.index') }}?student_id={{ $student->id }}" class="btn btn-link">
                        Lihat semua {{ $student->learningActivities->count() }} data aktivitas
                    </a>
                </div>
                @endif
                @else
                <div class="text-center py-5">
                    <i class="bi bi-journal-x fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Belum ada data aktivitas belajar</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Activity Chart -->
        @if($student->learningActivities->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Grafik Aktivitas Belajar</h5>
            </div>
            <div class="card-body">
                <canvas id="activityChart" height="250"></canvas>
            </div>
        </div>
        @endif

        <!-- Academic Scores -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Nilai Akademik</h5>
            </div>
            <div class="card-body p-0">
                @if($student->academicScores->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Semester</th>
                                <th>Nilai</th>
                                <th>Kategori</th>
                                <th>Dicatat oleh</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->academicScores as $score)
                            <tr>
                                <td>{{ $score->semester }}</td>
                                <td class="fw-medium">{{ $score->score }}</td>
                                <td>
                                    @php
                                        $catBadge = match($score->category) {
                                            'Tinggi' => 'bg-success',
                                            'Sedang' => 'bg-warning',
                                            'Rendah' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $catBadge }}">{{ $score->category }}</span>
                                </td>
                                <td>{{ $score->recordedBy?->name ?? '-' }}</td>
                                <td>{{ $score->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Belum ada data nilai akademik</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Prediction History -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Riwayat Prediksi</h5>
            </div>
            <div class="card-body p-0">
                @if($student->predictions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Model</th>
                                <th>Hasil</th>
                                <th>Confidence</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->predictions->take(5) as $prediction)
                            <tr>
                                <td>{{ $prediction->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $prediction->mlModel?->name ?? 'N/A' }}</td>
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
                                <td>
                                    <a href="{{ route('admin.predictions.show', $prediction) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-cpu fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Belum ada prediksi untuk siswa ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>
@endsection

@push('scripts')
@if($student->learningActivities->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('activityChart').getContext('2d');

    const activities = @json($student->learningActivities->take(6)->reverse()->values());
    const labels = activities.map(a => a.period);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Kehadiran (%)',
                    data: activities.map(a => a.attendance_rate),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.3
                },
                {
                    label: 'Disiplin',
                    data: activities.map(a => a.discipline_score),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.3
                },
                {
                    label: 'Partisipasi Diskusi',
                    data: activities.map(a => a.discussion_participation),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
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
@endif
@endpush
