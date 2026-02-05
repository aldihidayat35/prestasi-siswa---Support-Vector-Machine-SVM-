@extends('layouts.app')

@section('title', 'Detail Nilai Akademik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Detail Nilai Akademik</h4>
        <p class="text-muted mb-0">Informasi lengkap nilai akademik siswa</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.academic-scores.edit', $academicScore) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.academic-scores.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-journal-check me-2"></i>Nilai Per Mata Pelajaran</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @php
                        $subjects = [
                            ['name' => 'Matematika', 'value' => $academicScore->math_score, 'icon' => 'calculator'],
                            ['name' => 'Bahasa Indonesia', 'value' => $academicScore->indonesian_score, 'icon' => 'book'],
                            ['name' => 'Bahasa Inggris', 'value' => $academicScore->english_score, 'icon' => 'globe'],
                            ['name' => 'Fisika', 'value' => $academicScore->physics_score, 'icon' => 'lightning'],
                            ['name' => 'Kimia', 'value' => $academicScore->chemistry_score, 'icon' => 'droplet'],
                            ['name' => 'Biologi', 'value' => $academicScore->biology_score, 'icon' => 'tree'],
                        ];
                    @endphp

                    @foreach($subjects as $subject)
                        <div class="col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-{{ $subject['icon'] }} text-primary me-2"></i>
                                    <span class="text-muted">{{ $subject['name'] }}</span>
                                </div>
                                @if($subject['value'])
                                    @php
                                        $color = $subject['value'] >= 80 ? 'success' : ($subject['value'] >= 60 ? 'warning' : 'danger');
                                    @endphp
                                    <h3 class="mb-0 text-{{ $color }}">{{ number_format($subject['value'], 2) }}</h3>
                                @else
                                    <h3 class="mb-0 text-muted">-</h3>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-bar-chart me-2"></i>Visualisasi Nilai</h5>
            </div>
            <div class="card-body">
                @foreach($subjects as $subject)
                    @if($subject['value'])
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ $subject['name'] }}</span>
                                <span class="fw-semibold">{{ number_format($subject['value'], 2) }}</span>
                            </div>
                            @php
                                $color = $subject['value'] >= 80 ? 'success' : ($subject['value'] >= 60 ? 'warning' : 'danger');
                            @endphp
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-{{ $color }}" style="width: {{ $subject['value'] }}%"></div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Hasil Rata-rata -->
        <div class="card mb-4">
            <div class="card-body text-center py-4">
                @php
                    $avg = $academicScore->average_score;
                    if ($avg >= 80) {
                        $badge = 'success';
                        $label = 'Tinggi';
                        $icon = 'emoji-smile';
                    } elseif ($avg >= 60) {
                        $badge = 'warning';
                        $label = 'Sedang';
                        $icon = 'emoji-neutral';
                    } else {
                        $badge = 'danger';
                        $label = 'Rendah';
                        $icon = 'emoji-frown';
                    }
                @endphp
                <div class="display-1 text-{{ $badge }} mb-2">
                    <i class="bi bi-{{ $icon }}"></i>
                </div>
                <h1 class="display-4 fw-bold text-{{ $badge }} mb-2">{{ number_format($avg, 2) }}</h1>
                <span class="badge bg-{{ $badge }} fs-5 px-4 py-2">{{ $label }}</span>
                <p class="text-muted mt-3 mb-0">Rata-rata Nilai Akademik</p>
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
                        <td class="fw-semibold">{{ $academicScore->student->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">NISN</td>
                        <td>{{ $academicScore->student->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kelas</td>
                        <td>{{ $academicScore->student->class ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">JK</td>
                        <td>{{ $academicScore->student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
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
                        <td class="text-muted" width="50%">Semester</td>
                        <td class="fw-semibold">Semester {{ $academicScore->semester }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tahun Ajaran</td>
                        <td class="fw-semibold">{{ $academicScore->academic_year }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Diinput</td>
                        <td>{{ $academicScore->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Terakhir Update</td>
                        <td>{{ $academicScore->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
