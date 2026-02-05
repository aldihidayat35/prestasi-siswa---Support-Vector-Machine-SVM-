@extends('layouts.app')

@section('title', 'Detail Aktivitas Belajar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Detail Aktivitas Belajar</h4>
        <p class="text-muted mb-0">Informasi lengkap aktivitas belajar siswa</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.learning-activities.edit', $learningActivity) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.learning-activities.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-activity me-2"></i>Indikator Aktivitas Belajar</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @php
                        $indicators = [
                            ['name' => 'Tingkat Kehadiran', 'value' => $learningActivity->attendance_rate, 'unit' => '%', 'icon' => 'calendar-check', 'max' => 100],
                            ['name' => 'Durasi Belajar', 'value' => $learningActivity->study_duration, 'unit' => ' jam/hari', 'icon' => 'clock', 'max' => 8],
                            ['name' => 'Frekuensi Tugas', 'value' => $learningActivity->task_frequency, 'unit' => ' tugas', 'icon' => 'journal-text', 'max' => 50],
                            ['name' => 'Partisipasi Diskusi', 'value' => $learningActivity->discussion_participation, 'unit' => '%', 'icon' => 'chat-dots', 'max' => 100],
                            ['name' => 'Penggunaan Media', 'value' => $learningActivity->media_usage, 'unit' => '%', 'icon' => 'laptop', 'max' => 100],
                            ['name' => 'Skor Kedisiplinan', 'value' => $learningActivity->discipline_score, 'unit' => '%', 'icon' => 'shield-check', 'max' => 100],
                        ];
                    @endphp

                    @foreach($indicators as $indicator)
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-{{ $indicator['icon'] }} text-primary me-2"></i>
                                    <span class="text-muted small">{{ $indicator['name'] }}</span>
                                </div>
                                @php
                                    $pct = ($indicator['value'] / $indicator['max']) * 100;
                                    $color = $pct >= 75 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                                @endphp
                                <h3 class="mb-1 text-{{ $color }}">
                                    {{ number_format($indicator['value'], $indicator['max'] > 10 ? 1 : 2) }}{{ $indicator['unit'] }}
                                </h3>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-{{ $color }}" style="width: {{ min($pct, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if($learningActivity->notes)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-sticky me-2"></i>Catatan</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $learningActivity->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Summary -->
        <div class="card mb-4">
            <div class="card-body text-center py-4">
                @php
                    $avgScore = ($learningActivity->attendance_rate + $learningActivity->discussion_participation + $learningActivity->media_usage + $learningActivity->discipline_score) / 4;
                    if ($avgScore >= 75) {
                        $badge = 'success';
                        $label = 'Baik';
                        $icon = 'emoji-smile';
                    } elseif ($avgScore >= 50) {
                        $badge = 'warning';
                        $label = 'Cukup';
                        $icon = 'emoji-neutral';
                    } else {
                        $badge = 'danger';
                        $label = 'Kurang';
                        $icon = 'emoji-frown';
                    }
                @endphp
                <div class="display-1 text-{{ $badge }} mb-2">
                    <i class="bi bi-{{ $icon }}"></i>
                </div>
                <h2 class="fw-bold text-{{ $badge }} mb-2">{{ number_format($avgScore, 1) }}%</h2>
                <span class="badge bg-{{ $badge }} fs-6 px-4 py-2">{{ $label }}</span>
                <p class="text-muted mt-3 mb-0">Rata-rata Indikator Aktivitas</p>
            </div>
        </div>

        <!-- Info Siswa -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Info Siswa</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="40%">Nama</td>
                        <td class="fw-semibold">{{ $learningActivity->student->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">NISN</td>
                        <td>{{ $learningActivity->student->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kelas</td>
                        <td>{{ $learningActivity->student->class ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Info Periode -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-calendar me-2"></i>Info Periode</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="40%">Periode</td>
                        <td class="fw-semibold">{{ $learningActivity->period }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Guru</td>
                        <td>{{ $learningActivity->teacher->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Diinput</td>
                        <td>{{ $learningActivity->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
