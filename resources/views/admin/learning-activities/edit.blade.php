@extends('layouts.app')

@section('title', 'Edit Aktivitas Belajar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Edit Aktivitas Belajar</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.learning-activities.index') }}">Aktivitas Belajar</a></li>
                <li class="breadcrumb-item active">Edit</li>
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
                <h5 class="card-title mb-0">Form Edit Aktivitas</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.learning-activities.update', $learningActivity) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="student_id" class="form-label">Siswa</label>
                            <input type="text" class="form-control"
                                   value="{{ $learningActivity->student->name }} - {{ $learningActivity->student->nisn }}"
                                   readonly disabled>
                            <input type="hidden" name="student_id" value="{{ $learningActivity->student_id }}">
                        </div>
                        <div class="col-md-4">
                            <label for="period" class="form-label">Periode <span class="text-danger">*</span></label>
                            <input type="month" class="form-control @error('period') is-invalid @enderror"
                                   id="period" name="period"
                                   value="{{ old('period', \Carbon\Carbon::parse($learningActivity->period)->format('Y-m')) }}" required>
                            @error('period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-activity me-2"></i>Data Aktivitas</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="attendance_rate" class="form-label">Tingkat Kehadiran (%)</label>
                            <input type="number" step="0.01" min="0" max="100"
                                   class="form-control @error('attendance_rate') is-invalid @enderror"
                                   id="attendance_rate" name="attendance_rate"
                                   value="{{ old('attendance_rate', $learningActivity->attendance_rate) }}" required>
                            @error('attendance_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="study_duration" class="form-label">Durasi Belajar (jam/hari)</label>
                            <input type="number" step="0.1" min="0" max="24"
                                   class="form-control @error('study_duration') is-invalid @enderror"
                                   id="study_duration" name="study_duration"
                                   value="{{ old('study_duration', $learningActivity->study_duration) }}" required>
                            @error('study_duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="task_frequency" class="form-label">Frekuensi Tugas</label>
                            <input type="number" min="0" max="100"
                                   class="form-control @error('task_frequency') is-invalid @enderror"
                                   id="task_frequency" name="task_frequency"
                                   value="{{ old('task_frequency', $learningActivity->task_frequency) }}" required>
                            @error('task_frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="discussion_participation" class="form-label">Partisipasi Diskusi (%)</label>
                            <input type="number" step="0.01" min="0" max="100"
                                   class="form-control @error('discussion_participation') is-invalid @enderror"
                                   id="discussion_participation" name="discussion_participation"
                                   value="{{ old('discussion_participation', $learningActivity->discussion_participation) }}" required>
                            @error('discussion_participation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="media_usage" class="form-label">Penggunaan Media (%)</label>
                            <input type="number" step="0.01" min="0" max="100"
                                   class="form-control @error('media_usage') is-invalid @enderror"
                                   id="media_usage" name="media_usage"
                                   value="{{ old('media_usage', $learningActivity->media_usage) }}" required>
                            @error('media_usage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="discipline_score" class="form-label">Skor Kedisiplinan (0-100)</label>
                            <input type="number" step="0.01" min="0" max="100"
                                   class="form-control @error('discipline_score') is-invalid @enderror"
                                   id="discipline_score" name="discipline_score"
                                   value="{{ old('discipline_score', $learningActivity->discipline_score) }}" required>
                            @error('discipline_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update
                        </button>
                        <a href="{{ route('admin.learning-activities.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Info Siswa</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Nama:</td>
                        <td>{{ $learningActivity->student->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">NISN:</td>
                        <td>{{ $learningActivity->student->nisn }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kelas:</td>
                        <td>{{ $learningActivity->student->class }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Diinput Oleh:</td>
                        <td>{{ $learningActivity->recordedBy->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Input:</td>
                        <td>{{ $learningActivity->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
