@extends('layouts.app')

@section('title', 'Edit Nilai Akademik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Edit Nilai Akademik</h4>
        <p class="text-muted mb-0">Ubah data nilai akademik siswa</p>
    </div>
    <a href="{{ route('admin.academic-scores.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Edit Nilai</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.academic-scores.update', $academicScore) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Siswa</label>
                            <input type="text" class="form-control"
                                   value="{{ $academicScore->student->name ?? '-' }} ({{ $academicScore->student->nisn ?? '' }})"
                                   readonly disabled>
                            <input type="hidden" name="student_id" value="{{ $academicScore->student_id }}">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ old('semester', $academicScore->semester) == $i ? 'selected' : '' }}>
                                        Semester {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                            <select name="academic_year" class="form-select @error('academic_year') is-invalid @enderror" required>
                                <option value="2024/2025" {{ old('academic_year', $academicScore->academic_year) == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                <option value="2025/2026" {{ old('academic_year', $academicScore->academic_year) == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                                <option value="2023/2024" {{ old('academic_year', $academicScore->academic_year) == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                            </select>
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-book me-2"></i>Nilai Per Mata Pelajaran</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Matematika</label>
                            <input type="number" name="math_score" step="0.01" min="0" max="100"
                                   class="form-control @error('math_score') is-invalid @enderror"
                                   value="{{ old('math_score', $academicScore->math_score) }}" placeholder="0-100">
                            @error('math_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bahasa Indonesia</label>
                            <input type="number" name="indonesian_score" step="0.01" min="0" max="100"
                                   class="form-control @error('indonesian_score') is-invalid @enderror"
                                   value="{{ old('indonesian_score', $academicScore->indonesian_score) }}" placeholder="0-100">
                            @error('indonesian_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bahasa Inggris</label>
                            <input type="number" name="english_score" step="0.01" min="0" max="100"
                                   class="form-control @error('english_score') is-invalid @enderror"
                                   value="{{ old('english_score', $academicScore->english_score) }}" placeholder="0-100">
                            @error('english_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fisika</label>
                            <input type="number" name="physics_score" step="0.01" min="0" max="100"
                                   class="form-control @error('physics_score') is-invalid @enderror"
                                   value="{{ old('physics_score', $academicScore->physics_score) }}" placeholder="0-100">
                            @error('physics_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kimia</label>
                            <input type="number" name="chemistry_score" step="0.01" min="0" max="100"
                                   class="form-control @error('chemistry_score') is-invalid @enderror"
                                   value="{{ old('chemistry_score', $academicScore->chemistry_score) }}" placeholder="0-100">
                            @error('chemistry_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Biologi</label>
                            <input type="number" name="biology_score" step="0.01" min="0" max="100"
                                   class="form-control @error('biology_score') is-invalid @enderror"
                                   value="{{ old('biology_score', $academicScore->biology_score) }}" placeholder="0-100">
                            @error('biology_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rata-rata Nilai <span class="text-danger">*</span></label>
                            <input type="number" name="average_score" id="average_score" step="0.01" min="0" max="100"
                                   class="form-control @error('average_score') is-invalid @enderror"
                                   value="{{ old('average_score', $academicScore->average_score) }}" required>
                            @error('average_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <a href="#" onclick="calculateAverage(); return false;">
                                    <i class="bi bi-calculator"></i> Hitung ulang otomatis
                                </a>
                            </small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori Saat Ini</label>
                            @php
                                $avg = $academicScore->average_score;
                                if ($avg >= 80) {
                                    $badge = 'bg-success';
                                    $label = 'Tinggi';
                                } elseif ($avg >= 60) {
                                    $badge = 'bg-warning';
                                    $label = 'Sedang';
                                } else {
                                    $badge = 'bg-danger';
                                    $label = 'Rendah';
                                }
                            @endphp
                            <div class="pt-2">
                                <span class="badge {{ $badge }} fs-6">{{ $label }} ({{ number_format($avg, 2) }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.academic-scores.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Info Siswa</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Nama</td>
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

        <div class="card mt-3 bg-light">
            <div class="card-body">
                <h6><i class="bi bi-info-circle me-2"></i>Panduan Kategori</h6>
                <ul class="small text-muted mb-0">
                    <li class="mb-1"><span class="text-success fw-semibold">Tinggi:</span> Rata-rata ≥ 80</li>
                    <li class="mb-1"><span class="text-warning fw-semibold">Sedang:</span> Rata-rata 60 - 79</li>
                    <li><span class="text-danger fw-semibold">Rendah:</span> Rata-rata &lt; 60</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function calculateAverage() {
    const fields = ['math_score', 'indonesian_score', 'english_score', 'physics_score', 'chemistry_score', 'biology_score'];
    let total = 0;
    let count = 0;

    fields.forEach(field => {
        const val = parseFloat(document.querySelector(`[name="${field}"]`).value);
        if (!isNaN(val) && val > 0) {
            total += val;
            count++;
        }
    });

    if (count > 0) {
        document.getElementById('average_score').value = (total / count).toFixed(2);
    } else {
        alert('Masukkan minimal satu nilai mata pelajaran');
    }
}
</script>
@endpush
@endsection
