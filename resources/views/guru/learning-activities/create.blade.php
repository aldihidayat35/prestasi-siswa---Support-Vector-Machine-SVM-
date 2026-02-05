@extends('layouts.app')

@section('title', 'Input Aktivitas Belajar')
@section('header-title', 'Input Aktivitas Belajar Siswa')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Aktivitas Belajar</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.learning-activities.store') }}" method="POST">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="student_id" class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                            <select class="form-select @error('student_id') is-invalid @enderror"
                                    id="student_id" name="student_id" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                        {{ old('student_id', request('student_id')) == $student->id ? 'selected' : '' }}>
                                    {{ $student->nis }} - {{ $student->name }} ({{ $student->class }})
                                </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="period" class="form-label">Periode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('period') is-invalid @enderror"
                                   id="period" name="period" value="{{ old('period', date('Y-m')) }}"
                                   placeholder="cth: 2024-01" required>
                            @error('period')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: YYYY-MM (contoh: 2024-01)</small>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">Data Aktivitas Belajar</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="attendance_rate" class="form-label">
                                Tingkat Kehadiran (%) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.1" min="0" max="100"
                                   class="form-control @error('attendance_rate') is-invalid @enderror"
                                   id="attendance_rate" name="attendance_rate"
                                   value="{{ old('attendance_rate') }}" required>
                            @error('attendance_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="study_duration" class="form-label">
                                Durasi Belajar (jam/hari) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.5" min="0" max="24"
                                   class="form-control @error('study_duration') is-invalid @enderror"
                                   id="study_duration" name="study_duration"
                                   value="{{ old('study_duration') }}" required>
                            @error('study_duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="task_frequency" class="form-label">
                                Frekuensi Tugas <span class="text-danger">*</span>
                            </label>
                            <input type="number" min="0" max="100"
                                   class="form-control @error('task_frequency') is-invalid @enderror"
                                   id="task_frequency" name="task_frequency"
                                   value="{{ old('task_frequency') }}" required>
                            @error('task_frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Jumlah tugas yang dikerjakan</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="discussion_participation" class="form-label">
                                Partisipasi Diskusi (0-100) <span class="text-danger">*</span>
                            </label>
                            <input type="number" min="0" max="100"
                                   class="form-control @error('discussion_participation') is-invalid @enderror"
                                   id="discussion_participation" name="discussion_participation"
                                   value="{{ old('discussion_participation') }}" required>
                            @error('discussion_participation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="media_usage" class="form-label">
                                Penggunaan Media (0-100) <span class="text-danger">*</span>
                            </label>
                            <input type="number" min="0" max="100"
                                   class="form-control @error('media_usage') is-invalid @enderror"
                                   id="media_usage" name="media_usage"
                                   value="{{ old('media_usage') }}" required>
                            @error('media_usage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="discipline_score" class="form-label">
                                Skor Kedisiplinan (0-100) <span class="text-danger">*</span>
                            </label>
                            <input type="number" min="0" max="100"
                                   class="form-control @error('discipline_score') is-invalid @enderror"
                                   id="discipline_score" name="discipline_score"
                                   value="{{ old('discipline_score') }}" required>
                            @error('discipline_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                        <button type="submit" name="predict" value="1" class="btn btn-success">
                            <i class="bi bi-cpu me-1"></i>Simpan & Prediksi
                        </button>
                        <a href="{{ route('guru.learning-activities.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Guide -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Panduan Pengisian</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Kehadiran:</strong> Persentase kehadiran siswa dalam periode ini
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Durasi Belajar:</strong> Rata-rata jam belajar mandiri per hari
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Frekuensi Tugas:</strong> Jumlah tugas yang diselesaikan
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Partisipasi Diskusi:</strong> Tingkat keaktifan dalam diskusi kelas
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Penggunaan Media:</strong> Tingkat pemanfaatan media pembelajaran
                    </li>
                    <li>
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Kedisiplinan:</strong> Skor kedisiplinan siswa secara umum
                    </li>
                </ul>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Aktivitas Hari Ini</h5>
            </div>
            <div class="card-body text-center">
                <div class="fs-1 fw-bold text-primary">{{ $todayCount ?? 0 }}</div>
                <div class="text-muted">Data aktivitas diinput hari ini</div>
            </div>
        </div>
    </div>
</div>
@endsection
