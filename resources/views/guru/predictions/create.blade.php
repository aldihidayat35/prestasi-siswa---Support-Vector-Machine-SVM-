@extends('layouts.app')

@section('title', 'Prediksi Prestasi')
@section('header-title', 'Prediksi Prestasi Akademik Siswa')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Prediksi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.predictions.store') }}" method="POST" id="predictionForm">
                    @csrf

                    <!-- Student Selection -->
                    <div class="row mb-4">
                        <div class="col-md-8">
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
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-primary w-100" id="loadDataBtn">
                                <i class="bi bi-download me-1"></i>Load Data Terbaru
                            </button>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">
                        <i class="bi bi-journal-text me-2"></i>Data Aktivitas Belajar
                    </h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="attendance_rate" class="form-label">
                                Tingkat Kehadiran (%) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="0" max="100"
                                       class="form-control @error('attendance_rate') is-invalid @enderror"
                                       id="attendance_rate" name="attendance_rate"
                                       value="{{ old('attendance_rate') }}" required>
                                <span class="input-group-text">%</span>
                            </div>
                            @error('attendance_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="study_duration" class="form-label">
                                Durasi Belajar (jam/hari) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.5" min="0" max="24"
                                       class="form-control @error('study_duration') is-invalid @enderror"
                                       id="study_duration" name="study_duration"
                                       value="{{ old('study_duration') }}" required>
                                <span class="input-group-text">jam</span>
                            </div>
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
                        <button type="submit" class="btn btn-success" id="predictBtn">
                            <i class="bi bi-cpu me-1"></i>Prediksi Sekarang
                        </button>
                        <a href="{{ route('guru.predictions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Model Info -->
        @if($activeModel)
        <div class="card mb-4 border-success">
            <div class="card-header bg-success bg-opacity-10">
                <h5 class="card-title mb-0 text-success">
                    <i class="bi bi-check-circle me-2"></i>Model Aktif
                </h5>
            </div>
            <div class="card-body">
                <h6>{{ $activeModel->name }}</h6>
                <p class="text-muted small mb-2">Kernel: {{ strtoupper($activeModel->kernel) }}</p>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Akurasi:</span>
                    <span class="fw-bold text-success">{{ number_format($activeModel->accuracy * 100, 1) }}%</span>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Tidak ada model aktif!</strong>
            <p class="mb-0 small">Hubungi admin untuk mengaktifkan model prediksi.</p>
        </div>
        @endif

        <!-- Info Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Kategori Prediksi</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3 p-2 bg-success bg-opacity-10 rounded">
                    <span class="badge bg-success me-3 px-3 py-2">Tinggi</span>
                    <div class="small">Prediksi nilai akademik ≥ 80</div>
                </div>
                <div class="d-flex align-items-center mb-3 p-2 bg-warning bg-opacity-10 rounded">
                    <span class="badge bg-warning me-3 px-3 py-2">Sedang</span>
                    <div class="small">Prediksi nilai akademik 60-79</div>
                </div>
                <div class="d-flex align-items-center p-2 bg-danger bg-opacity-10 rounded">
                    <span class="badge bg-danger me-3 px-3 py-2">Rendah</span>
                    <div class="small">Prediksi nilai akademik < 60</div>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tips</h5>
            </div>
            <div class="card-body small">
                <p class="mb-2">
                    <i class="bi bi-lightbulb text-warning me-2"></i>
                    Klik "Load Data Terbaru" untuk mengisi form dengan data aktivitas terakhir siswa.
                </p>
                <p class="mb-0">
                    <i class="bi bi-info-circle text-info me-2"></i>
                    Hasil prediksi berdasarkan algoritma SVM dengan kernel {{ $activeModel?->kernel ?? 'RBF' }}.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const loadDataBtn = document.getElementById('loadDataBtn');

    loadDataBtn.addEventListener('click', function() {
        const studentId = studentSelect.value;
        if (!studentId) {
            alert('Pilih siswa terlebih dahulu');
            return;
        }

        loadDataBtn.disabled = true;
        loadDataBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';

        fetch(`/api/students/${studentId}/latest-activity`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.activity) {
                    document.getElementById('attendance_rate').value = data.activity.attendance_rate;
                    document.getElementById('study_duration').value = data.activity.study_duration;
                    document.getElementById('task_frequency').value = data.activity.task_frequency;
                    document.getElementById('discussion_participation').value = data.activity.discussion_participation;
                    document.getElementById('media_usage').value = data.activity.media_usage;
                    document.getElementById('discipline_score').value = data.activity.discipline_score;
                } else {
                    alert('Tidak ada data aktivitas untuk siswa ini');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data');
            })
            .finally(() => {
                loadDataBtn.disabled = false;
                loadDataBtn.innerHTML = '<i class="bi bi-download me-1"></i>Load Data Terbaru';
            });
    });

    document.getElementById('predictionForm').addEventListener('submit', function() {
        const btn = document.getElementById('predictBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
    });
});
</script>
@endpush
