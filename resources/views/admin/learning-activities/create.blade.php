@extends('layouts.app')

@section('title', 'Tambah Aktivitas Belajar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Tambah Aktivitas Belajar</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.learning-activities.index') }}">Aktivitas Belajar</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.learning-activities.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Aktivitas Belajar</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.learning-activities.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="student_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select class="form-select @error('student_id') is-invalid @enderror"
                                    id="student_id" name="student_id" required>
                                <option value="">Pilih Siswa</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} - {{ $student->nisn }} ({{ $student->class }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="period" class="form-label">Periode <span class="text-danger">*</span></label>
                            <input type="month" class="form-control @error('period') is-invalid @enderror"
                                   id="period" name="period" value="{{ old('period', date('Y-m')) }}" required>
                            @error('period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-activity me-2"></i>Data Aktivitas</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="attendance_rate" class="form-label">Tingkat Kehadiran (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100"
                                   class="form-control @error('attendance_rate') is-invalid @enderror"
                                   id="attendance_rate" name="attendance_rate"
                                   value="{{ old('attendance_rate', 85) }}" required>
                            @error('attendance_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="study_duration" class="form-label">Durasi Belajar (jam/hari) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" min="0" max="24"
                                   class="form-control @error('study_duration') is-invalid @enderror"
                                   id="study_duration" name="study_duration"
                                   value="{{ old('study_duration', 4) }}" required>
                            @error('study_duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="task_frequency" class="form-label">Frekuensi Tugas <span class="text-danger">*</span></label>
                            <input type="number" min="0" max="100"
                                   class="form-control @error('task_frequency') is-invalid @enderror"
                                   id="task_frequency" name="task_frequency"
                                   value="{{ old('task_frequency', 15) }}" required>
                            @error('task_frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="discussion_participation" class="form-label">Partisipasi Diskusi (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100"
                                   class="form-control @error('discussion_participation') is-invalid @enderror"
                                   id="discussion_participation" name="discussion_participation"
                                   value="{{ old('discussion_participation', 70) }}" required>
                            @error('discussion_participation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="media_usage" class="form-label">Penggunaan Media (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100"
                                   class="form-control @error('media_usage') is-invalid @enderror"
                                   id="media_usage" name="media_usage"
                                   value="{{ old('media_usage', 65) }}" required>
                            @error('media_usage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="discipline_score" class="form-label">Skor Kedisiplinan (0-100) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100"
                                   class="form-control @error('discipline_score') is-invalid @enderror"
                                   id="discipline_score" name="discipline_score"
                                   value="{{ old('discipline_score', 80) }}" required>
                            @error('discipline_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.learning-activities.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-info-circle me-2"></i>Panduan Input</h6>
                <ul class="small text-muted mb-0">
                    <li><strong>Kehadiran:</strong> Persentase kehadiran di kelas (0-100%)</li>
                    <li><strong>Durasi Belajar:</strong> Rata-rata jam belajar per hari</li>
                    <li><strong>Frekuensi Tugas:</strong> Jumlah tugas yang dikerjakan</li>
                    <li><strong>Partisipasi Diskusi:</strong> Tingkat keaktifan dalam diskusi</li>
                    <li><strong>Penggunaan Media:</strong> Pemanfaatan media pembelajaran</li>
                    <li><strong>Skor Kedisiplinan:</strong> Nilai kedisiplinan siswa</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
