@extends('layouts.app')

@section('title', 'Edit Siswa')
@section('header-title', 'Edit Data Siswa')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Edit Siswa: {{ $student->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.students.update', $student) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nis" class="form-label">Nomor Induk Siswa (NIS) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nis') is-invalid @enderror"
                                   id="nis" name="nis" value="{{ old('nis', $student->nis) }}" required>
                            @error('nis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $student->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="class" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <select class="form-select @error('class') is-invalid @enderror" id="class" name="class" required>
                                <option value="">Pilih Kelas</option>
                                <option value="XI IPA 1" {{ old('class', $student->class) == 'XI IPA 1' ? 'selected' : '' }}>XI IPA 1</option>
                                <option value="XI IPA 2" {{ old('class', $student->class) == 'XI IPA 2' ? 'selected' : '' }}>XI IPA 2</option>
                                <option value="XI IPS 1" {{ old('class', $student->class) == 'XI IPS 1' ? 'selected' : '' }}>XI IPS 1</option>
                                <option value="XI IPS 2" {{ old('class', $student->class) == 'XI IPS 2' ? 'selected' : '' }}>XI IPS 2</option>
                            </select>
                            @error('class')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="">Pilih</option>
                                <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birth_place" class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control @error('birth_place') is-invalid @enderror"
                                   id="birth_place" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}">
                            @error('birth_place')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="birth_date" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                   id="birth_date" name="birth_date" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}">
                            @error('birth_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="2">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="parent_name" class="form-label">Nama Orang Tua</label>
                            <input type="text" class="form-control @error('parent_name') is-invalid @enderror"
                                   id="parent_name" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}">
                            @error('parent_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="parent_phone" class="form-label">No. HP Orang Tua</label>
                            <input type="text" class="form-control @error('parent_phone') is-invalid @enderror"
                                   id="parent_phone" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}">
                            @error('parent_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                   value="1" {{ old('is_active', $student->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Siswa Aktif
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Update
                        </button>
                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-info">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Info Siswa</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-lg mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-person fs-1 text-muted"></i>
                    </div>
                </div>

                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">NIS</td>
                        <td class="text-end fw-medium">{{ $student->nis }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kelas</td>
                        <td class="text-end">{{ $student->class }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Terdaftar</td>
                        <td class="text-end">{{ $student->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Aktivitas</td>
                        <td class="text-end">{{ $student->learningActivities->count() }} data</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Prediksi</td>
                        <td class="text-end">{{ $student->predictions->count() }} hasil</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
