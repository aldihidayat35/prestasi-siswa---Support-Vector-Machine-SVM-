@extends('layouts.app')

@section('title', 'Data Aktivitas Belajar')
@section('header-title', 'Data Aktivitas Belajar')

@section('content')
<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('guru.learning-activities.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Cari Siswa</label>
                <input type="text" name="search" class="form-control" placeholder="Nama atau NIS..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Kelas</label>
                <select name="class" class="form-select">
                    <option value="">Semua Kelas</option>
                    <option value="XI IPA 1" {{ request('class') == 'XI IPA 1' ? 'selected' : '' }}>XI IPA 1</option>
                    <option value="XI IPA 2" {{ request('class') == 'XI IPA 2' ? 'selected' : '' }}>XI IPA 2</option>
                    <option value="XI IPS 1" {{ request('class') == 'XI IPS 1' ? 'selected' : '' }}>XI IPS 1</option>
                    <option value="XI IPS 2" {{ request('class') == 'XI IPS 2' ? 'selected' : '' }}>XI IPS 2</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Periode</label>
                <input type="text" name="period" class="form-control" placeholder="cth: 2024-01" value="{{ request('period') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Data Saya</label>
                <select name="mine" class="form-select">
                    <option value="">Semua Data</option>
                    <option value="1" {{ request('mine') == '1' ? 'selected' : '' }}>Data Saya Saja</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search me-1"></i>Cari
                </button>
                <a href="{{ route('guru.learning-activities.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Actions -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Daftar Aktivitas</h5>
    <a href="{{ route('guru.learning-activities.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Input Aktivitas Baru
    </a>
</div>

<!-- Activities Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Periode</th>
                        <th class="text-center">Kehadiran</th>
                        <th class="text-center">Durasi</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">Diskusi</th>
                        <th class="text-center">Media</th>
                        <th class="text-center">Disiplin</th>
                        <th>Dicatat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>{{ $loop->iteration + ($activities->currentPage() - 1) * $activities->perPage() }}</td>
                        <td>
                            <div class="fw-medium">{{ $activity->student?->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $activity->student?->nis ?? '' }}</small>
                        </td>
                        <td><span class="badge bg-info">{{ $activity->student?->class ?? '-' }}</span></td>
                        <td>{{ $activity->period }}</td>
                        <td class="text-center">
                            @php
                                $attendanceClass = $activity->attendance_rate >= 90 ? 'text-success' : ($activity->attendance_rate >= 75 ? 'text-warning' : 'text-danger');
                            @endphp
                            <span class="{{ $attendanceClass }} fw-medium">{{ $activity->attendance_rate }}%</span>
                        </td>
                        <td class="text-center">{{ $activity->study_duration }} jam</td>
                        <td class="text-center">{{ $activity->task_frequency }}</td>
                        <td class="text-center">{{ $activity->discussion_participation }}</td>
                        <td class="text-center">{{ $activity->media_usage }}</td>
                        <td class="text-center">
                            @php
                                $disciplineClass = $activity->discipline_score >= 80 ? 'text-success' : ($activity->discipline_score >= 60 ? 'text-warning' : 'text-danger');
                            @endphp
                            <span class="{{ $disciplineClass }} fw-medium">{{ $activity->discipline_score }}</span>
                        </td>
                        <td>
                            <div>{{ $activity->recordedBy?->name ?? '-' }}</div>
                            <small class="text-muted">{{ $activity->created_at->format('d M Y') }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('guru.learning-activities.show', $activity) }}" class="btn btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($activity->recorded_by == auth()->id())
                                <a href="{{ route('guru.learning-activities.edit', $activity) }}" class="btn btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center py-5">
                            <i class="bi bi-journal-x fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada data aktivitas</p>
                            <a href="{{ route('guru.learning-activities.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Input Aktivitas Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($activities->hasPages())
    <div class="card-footer">
        {{ $activities->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
