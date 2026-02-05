@extends('layouts.app')

@section('title', 'Aktivitas Belajar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Aktivitas Belajar</h4>
        <p class="text-muted mb-0">Data aktivitas belajar siswa</p>
    </div>
    <a href="{{ route('admin.learning-activities.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah Aktivitas
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.learning-activities.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Cari Siswa</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Nama/NISN..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Kelas</label>
                <select name="class" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach(['X IPA 1', 'X IPA 2', 'XI IPA 1', 'XI IPA 2', 'XII IPA 1', 'XII IPA 2'] as $class)
                        <option value="{{ $class }}" {{ request('class') == $class ? 'selected' : '' }}>
                            {{ $class }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Periode</label>
                <input type="month" name="period" class="form-control" value="{{ request('period') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Guru Input</label>
                <select name="recorded_by" class="form-select">
                    <option value="">Semua Guru</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('recorded_by') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('admin.learning-activities.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Daftar Aktivitas Belajar</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th class="text-center">Kehadiran</th>
                        <th class="text-center">Durasi Belajar</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">Diskusi</th>
                        <th class="text-center">Media</th>
                        <th class="text-center">Disiplin</th>
                        <th>Periode</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $index => $activity)
                        <tr>
                            <td>{{ $activities->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-semibold">{{ $activity->student->name ?? '-' }}</div>
                                <small class="text-muted">{{ $activity->student->nisn ?? '' }}</small>
                            </td>
                            <td>{{ $activity->student->class ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $activity->attendance_rate >= 80 ? 'bg-success' : ($activity->attendance_rate >= 60 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ number_format($activity->attendance_rate, 1) }}%
                                </span>
                            </td>
                            <td class="text-center">{{ number_format($activity->study_duration, 1) }} jam</td>
                            <td class="text-center">{{ $activity->task_frequency }}</td>
                            <td class="text-center">{{ number_format($activity->discussion_participation, 1) }}%</td>
                            <td class="text-center">{{ number_format($activity->media_usage, 1) }}%</td>
                            <td class="text-center">{{ number_format($activity->discipline_score, 1) }}</td>
                            <td>{{ \Carbon\Carbon::parse($activity->period)->format('M Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.learning-activities.edit', $activity) }}"
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.learning-activities.destroy', $activity) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                Belum ada data aktivitas belajar
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
