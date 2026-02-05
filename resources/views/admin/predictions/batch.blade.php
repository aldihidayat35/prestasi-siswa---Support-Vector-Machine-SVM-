@extends('layouts.app')

@section('title', 'Prediksi Batch')
@section('header-title', 'Prediksi Batch - Multiple Siswa')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pilih Siswa untuk Prediksi Batch</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.predictions.batch.store') }}" method="POST">
                    @csrf

                    <!-- Filter -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Filter Kelas</label>
                            <select class="form-select" id="classFilter">
                                <option value="">Semua Kelas</option>
                                <option value="XI IPA 1">XI IPA 1</option>
                                <option value="XI IPA 2">XI IPA 2</option>
                                <option value="XI IPS 1">XI IPS 1</option>
                                <option value="XI IPS 2">XI IPS 2</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pilih Semua</label>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="selectAll">
                                    <i class="bi bi-check-all me-1"></i>Pilih Semua
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAll">
                                    <i class="bi bi-x me-1"></i>Batal Semua
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Terpilih</label>
                            <div class="fs-4 fw-bold text-primary" id="selectedCount">0 siswa</div>
                        </div>
                    </div>

                    <div class="alert alert-info small mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Prediksi batch akan menggunakan data aktivitas belajar terbaru dari setiap siswa.
                        Siswa tanpa data aktivitas akan dilewati.
                    </div>

                    <!-- Student List -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th width="40">
                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                    </th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Data Aktivitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr class="student-row" data-class="{{ $student->class }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input student-checkbox"
                                               name="student_ids[]" value="{{ $student->id }}"
                                               {{ $student->learningActivities->count() == 0 ? 'disabled' : '' }}>
                                    </td>
                                    <td>{{ $student->nis }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td><span class="badge bg-info">{{ $student->class }}</span></td>
                                    <td>
                                        @if($student->learningActivities->count() > 0)
                                            <span class="badge bg-success">
                                                {{ $student->learningActivities->count() }} data
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Tidak ada</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-cpu me-1"></i>Jalankan Prediksi Batch
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
        <!-- Active Model -->
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
                </table>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Tidak ada model aktif!</strong>
            <p class="mb-0 small">Silakan latih dan aktifkan model terlebih dahulu.</p>
        </div>
        @endif

        <!-- Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi</h5>
            </div>
            <div class="card-body small">
                <ul class="mb-0">
                    <li class="mb-2">Prediksi batch memproses multiple siswa sekaligus</li>
                    <li class="mb-2">Setiap siswa menggunakan data aktivitas terbaru</li>
                    <li class="mb-2">Siswa tanpa data aktivitas akan dilewati</li>
                    <li>Hasil prediksi tersimpan di riwayat prediksi</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classFilter = document.getElementById('classFilter');
    const checkAll = document.getElementById('checkAll');
    const selectAll = document.getElementById('selectAll');
    const deselectAll = document.getElementById('deselectAll');
    const studentRows = document.querySelectorAll('.student-row');
    const studentCheckboxes = document.querySelectorAll('.student-checkbox:not(:disabled)');
    const selectedCount = document.getElementById('selectedCount');

    function updateCount() {
        const count = document.querySelectorAll('.student-checkbox:checked').length;
        selectedCount.textContent = count + ' siswa';
    }

    // Filter by class
    classFilter.addEventListener('change', function() {
        const filterValue = this.value;
        studentRows.forEach(row => {
            if (filterValue === '' || row.dataset.class === filterValue) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Check all visible
    checkAll.addEventListener('change', function() {
        const filterValue = classFilter.value;
        studentCheckboxes.forEach(checkbox => {
            const row = checkbox.closest('.student-row');
            if (filterValue === '' || row.dataset.class === filterValue) {
                checkbox.checked = this.checked;
            }
        });
        updateCount();
    });

    selectAll.addEventListener('click', function() {
        const filterValue = classFilter.value;
        studentCheckboxes.forEach(checkbox => {
            const row = checkbox.closest('.student-row');
            if (filterValue === '' || row.dataset.class === filterValue) {
                checkbox.checked = true;
            }
        });
        updateCount();
    });

    deselectAll.addEventListener('click', function() {
        studentCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateCount();
    });

    // Update count on checkbox change
    studentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCount);
    });

    // Form submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const count = document.querySelectorAll('.student-checkbox:checked').length;
        if (count === 0) {
            e.preventDefault();
            alert('Pilih minimal satu siswa untuk prediksi');
            return;
        }

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses ' + count + ' siswa...';
    });
});
</script>
@endpush
