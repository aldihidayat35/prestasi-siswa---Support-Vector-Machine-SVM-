@extends('layouts.app')

@section('title', 'Latih Model SVM')
@section('header-title', 'Latih Model SVM Baru')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Konfigurasi Training Model</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ml-models.store') }}" method="POST" id="trainingForm">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Model <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', 'SVM Model ' . date('Y-m-d H:i')) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="kernel" class="form-label">Kernel <span class="text-danger">*</span></label>
                            <select class="form-select @error('kernel') is-invalid @enderror" id="kernel" name="kernel" required>
                                <option value="rbf" {{ old('kernel') == 'rbf' ? 'selected' : '' }}>RBF (Radial Basis Function)</option>
                                <option value="linear" {{ old('kernel') == 'linear' ? 'selected' : '' }}>Linear</option>
                                <option value="poly" {{ old('kernel') == 'poly' ? 'selected' : '' }}>Polynomial</option>
                                <option value="sigmoid" {{ old('kernel') == 'sigmoid' ? 'selected' : '' }}>Sigmoid</option>
                            </select>
                            @error('kernel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kernel RBF umumnya memberikan hasil terbaik untuk klasifikasi multi-kelas</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="c_param" class="form-label">Parameter C <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" class="form-control @error('c_param') is-invalid @enderror"
                                   id="c_param" name="c_param" value="{{ old('c_param', 1.0) }}" required>
                            @error('c_param')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Regularization parameter. Nilai besar = less regularization</small>
                        </div>

                        <div class="col-md-4">
                            <label for="gamma" class="form-label">Gamma</label>
                            <select class="form-select @error('gamma') is-invalid @enderror" id="gamma" name="gamma">
                                <option value="scale" {{ old('gamma') == 'scale' ? 'selected' : '' }}>Scale (1 / (n_features * X.var()))</option>
                                <option value="auto" {{ old('gamma') == 'auto' ? 'selected' : '' }}>Auto (1 / n_features)</option>
                            </select>
                            @error('gamma')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kernel coefficient untuk RBF, Poly, Sigmoid</small>
                        </div>

                        <div class="col-md-4">
                            <label for="degree" class="form-label">Degree</label>
                            <input type="number" min="1" max="10" class="form-control @error('degree') is-invalid @enderror"
                                   id="degree" name="degree" value="{{ old('degree', 3) }}">
                            @error('degree')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Degree of polynomial kernel (hanya untuk kernel poly)</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="2">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="set_active" id="set_active" value="1" checked>
                            <label class="form-check-label" for="set_active">
                                Set sebagai model aktif setelah training
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Informasi Dataset</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Total Data:</strong> {{ $datasetStats['total'] ?? 0 }}
                            </div>
                            <div class="col-md-4">
                                <strong>Data Training:</strong> {{ $datasetStats['training'] ?? 0 }}
                            </div>
                            <div class="col-md-4">
                                <strong>Data Testing:</strong> {{ $datasetStats['testing'] ?? 0 }}
                            </div>
                        </div>
                        @if(isset($datasetStats['distribution']))
                        <hr>
                        <strong>Distribusi Label:</strong>
                        @foreach($datasetStats['distribution'] as $label => $count)
                            <span class="badge bg-secondary ms-2">{{ $label }}: {{ $count }}</span>
                        @endforeach
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="trainBtn">
                            <i class="bi bi-cpu me-1"></i>Mulai Training
                        </button>
                        <a href="{{ route('admin.ml-models.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Kernel Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Kernel SVM</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="kernelAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kernelRBF">
                                RBF (Radial Basis Function)
                            </button>
                        </h2>
                        <div id="kernelRBF" class="accordion-collapse collapse" data-bs-parent="#kernelAccordion">
                            <div class="accordion-body small">
                                Kernel paling populer untuk klasifikasi. Baik untuk data non-linear dan multiclass.
                                Parameter penting: C dan gamma.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kernelLinear">
                                Linear
                            </button>
                        </h2>
                        <div id="kernelLinear" class="accordion-collapse collapse" data-bs-parent="#kernelAccordion">
                            <div class="accordion-body small">
                                Kernel sederhana untuk data yang dapat dipisahkan secara linear.
                                Cepat dan efisien untuk dataset besar.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kernelPoly">
                                Polynomial
                            </button>
                        </h2>
                        <div id="kernelPoly" class="accordion-collapse collapse" data-bs-parent="#kernelAccordion">
                            <div class="accordion-body small">
                                Baik untuk data dengan hubungan polinomial.
                                Parameter degree menentukan kompleksitas.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kernelSigmoid">
                                Sigmoid
                            </button>
                        </h2>
                        <div id="kernelSigmoid" class="accordion-collapse collapse" data-bs-parent="#kernelAccordion">
                            <div class="accordion-body small">
                                Mirip dengan fungsi aktivasi neural network.
                                Cocok untuk aplikasi tertentu.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tips Training</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Pastikan data training sudah memadai (minimal 50 data per kelas)
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Untuk hasil terbaik, coba berbagai kombinasi kernel dan parameter
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Perhatikan metrik F1-Score untuk evaluasi model yang seimbang
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        Cross-validation 5-fold akan dilakukan secara otomatis
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('trainingForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('trainBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Training...';
});

// Toggle degree field based on kernel selection
document.getElementById('kernel').addEventListener('change', function() {
    const degreeInput = document.getElementById('degree');
    const gammaSelect = document.getElementById('gamma');

    if (this.value === 'linear') {
        degreeInput.disabled = true;
        gammaSelect.disabled = true;
    } else if (this.value === 'poly') {
        degreeInput.disabled = false;
        gammaSelect.disabled = false;
    } else {
        degreeInput.disabled = true;
        gammaSelect.disabled = false;
    }
});
</script>
@endpush
