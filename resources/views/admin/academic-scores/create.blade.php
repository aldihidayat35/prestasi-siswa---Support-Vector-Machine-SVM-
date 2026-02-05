@extends('layouts.app')

@section('title', 'Tambah Nilai Akademik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Tambah Nilai Akademik</h4>
        <p class="text-muted mb-0">Masukkan data nilai akademik siswa</p>
    </div>
    <a href="{{ route('admin.academic-scores.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Nilai Akademik</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.academic-scores.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Siswa <span class="text-danger">*</span></label>
                            <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach($students ?? [] as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} ({{ $student->nisn }}) - {{ $student->class }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
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
                                <option value="2024/2025" {{ old('academic_year') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                <option value="2025/2026" {{ old('academic_year') == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                                <option value="2023/2024" {{ old('academic_year') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
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
                                   value="{{ old('math_score') }}" placeholder="0-100">
                            @error('math_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bahasa Indonesia</label>
                            <input type="number" name="indonesian_score" step="0.01" min="0" max="100"
                                   class="form-control @error('indonesian_score') is-invalid @enderror"
                                   value="{{ old('indonesian_score') }}" placeholder="0-100">
                            @error('indonesian_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bahasa Inggris</label>
                            <input type="number" name="english_score" step="0.01" min="0" max="100"
                                   class="form-control @error('english_score') is-invalid @enderror"
                                   value="{{ old('english_score') }}" placeholder="0-100">
                            @error('english_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fisika</label>
                            <input type="number" name="physics_score" step="0.01" min="0" max="100"
                                   class="form-control @error('physics_score') is-invalid @enderror"
                                   value="{{ old('physics_score') }}" placeholder="0-100">
                            @error('physics_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kimia</label>
                            <input type="number" name="chemistry_score" step="0.01" min="0" max="100"
                                   class="form-control @error('chemistry_score') is-invalid @enderror"
                                   value="{{ old('chemistry_score') }}" placeholder="0-100">
                            @error('chemistry_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Biologi</label>
                            <input type="number" name="biology_score" step="0.01" min="0" max="100"
                                   class="form-control @error('biology_score') is-invalid @enderror"
                                   value="{{ old('biology_score') }}" placeholder="0-100">
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
                                   value="{{ old('average_score') }}" required placeholder="Otomatis dihitung">
                            @error('average_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <a href="#" onclick="calculateAverage(); return false;">
                                    <i class="bi bi-calculator"></i> Hitung otomatis
                                </a>
                            </small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.academic-scores.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6><i class="bi bi-info-circle me-2"></i>Panduan</h6>
                <ul class="small text-muted mb-0">
                    <li class="mb-2">Pilih siswa yang akan diinputkan nilai</li>
                    <li class="mb-2">Semester 1-2 untuk kelas X, 3-4 untuk kelas XI, 5-6 untuk kelas XII</li>
                    <li class="mb-2">Input nilai per mata pelajaran (opsional)</li>
                    <li class="mb-2">Klik "Hitung otomatis" untuk menghitung rata-rata</li>
                    <li>Kategori: <span class="text-success">Tinggi ≥80</span>,
                        <span class="text-warning">Sedang 60-79</span>,
                        <span class="text-danger">Rendah &lt;60</span></li>
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
