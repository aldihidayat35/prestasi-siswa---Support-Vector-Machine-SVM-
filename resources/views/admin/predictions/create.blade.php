@extends('layouts.app')

@section('title', 'Prediksi Baru')
@section('header-title', 'Prediksi Prestasi Akademik')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Prediksi</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.predictions.store') }}" method="POST" id="predictionForm">
                    @csrf

                    <!-- Student Selection -->
                    <div class="mb-4">
                        <label for="student_id" class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                        <select class="form-select @error('student_id') is-invalid @enderror"
                                id="student_id" name="student_id" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                    data-class="{{ $student->class }}"
                                    {{ old('student_id', request('student_id')) == $student->id ? 'selected' : '' }}>
                                {{ $student->nis }} - {{ $student->name }} ({{ $student->class }})
                            </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            <small class="text-muted">Persentase kehadiran siswa (0-100%)</small>
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
                            <small class="text-muted">Rata-rata jam belajar per hari</small>
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
                            <small class="text-muted">Jumlah tugas yang dikerjakan per periode</small>
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
                            <small class="text-muted">Skor keaktifan dalam diskusi</small>
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
                            <small class="text-muted">Skor pemanfaatan media pembelajaran</small>
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
                            <small class="text-muted">Skor kedisiplinan siswa</small>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="predictBtn">
                            <i class="bi bi-cpu me-1"></i>Prediksi
                        </button>
                        <button type="button" class="btn btn-success" id="loadLatestBtn">
                            <i class="bi bi-arrow-down-circle me-1"></i>Load Data Terbaru
                        </button>
                        <a href="{{ route('admin.predictions.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Active Model Info -->
        @if($activeModel)
        <div class="card mb-4 border-success">
            <div class="card-header bg-success bg-opacity-10">
                <h5 class="card-title mb-0 text-success">
                    <i class="bi bi-check-circle me-2"></i>Model Aktif
                </h5>
            </div>
            <div class="card-body">
                <h6>{{ $activeModel->name }}</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Kernel</td>
                        <td class="text-end text-uppercase">{{ $activeModel->kernel }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Akurasi</td>
                        <td class="text-end text-success fw-bold">{{ number_format($activeModel->accuracy * 100, 1) }}%</td>
                    </tr>
                    <tr>
                        <td class="text-muted">F1-Score</td>
                        <td class="text-end">{{ number_format($activeModel->f1_score * 100, 1) }}%</td>
                    </tr>
                </table>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Tidak ada model aktif!</strong>
            <p class="mb-0 small">Silakan latih model terlebih dahulu sebelum melakukan prediksi.</p>
            <a href="{{ route('admin.ml-models.create') }}" class="btn btn-sm btn-warning mt-2">
                Latih Model
            </a>
        </div>
        @endif

        <!-- Quick Guide -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Panduan Input</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2">
                        <strong>Kehadiran:</strong> Persentase kehadiran dalam kelas (0-100%)
                    </li>
                    <li class="mb-2">
                        <strong>Durasi Belajar:</strong> Rata-rata jam belajar mandiri per hari
                    </li>
                    <li class="mb-2">
                        <strong>Frekuensi Tugas:</strong> Jumlah tugas yang dikerjakan
                    </li>
                    <li class="mb-2">
                        <strong>Partisipasi Diskusi:</strong> Keaktifan dalam diskusi (0-100)
                    </li>
                    <li class="mb-2">
                        <strong>Media Usage:</strong> Penggunaan media belajar (0-100)
                    </li>
                    <li>
                        <strong>Kedisiplinan:</strong> Skor kedisiplinan siswa (0-100)
                    </li>
                </ul>
            </div>
        </div>

        <!-- Prediction Categories -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Kategori Prediksi</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-success me-2 px-3">Tinggi</span>
                    <small class="text-muted">Nilai ≥ 80</small>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-warning me-2 px-3">Sedang</span>
                    <small class="text-muted">Nilai 60 - 79</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-danger me-2 px-3">Rendah</span>
                    <small class="text-muted">Nilai < 60</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hasil Prediksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center" id="resultContent">
                <!-- Will be filled by JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="" class="btn btn-primary" id="viewDetailBtn">Lihat Detail</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const loadLatestBtn = document.getElementById('loadLatestBtn');

    // Load latest activity data for selected student
    loadLatestBtn.addEventListener('click', function() {
        const studentId = studentSelect.value;
        if (!studentId) {
            alert('Pilih siswa terlebih dahulu');
            return;
        }

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
            });
    });

    // Form submission with loading state
    document.getElementById('predictionForm').addEventListener('submit', function() {
        const btn = document.getElementById('predictBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
    });
});
</script>
@endpush
