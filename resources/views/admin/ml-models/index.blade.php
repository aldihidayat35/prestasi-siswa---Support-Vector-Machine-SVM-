@extends('layouts.app')

@section('title', 'Model Machine Learning')
@section('header-title', 'Manajemen Model SVM')

@section('content')
<!-- Active Model Info -->
@if($activeModel)
<div class="card border-success mb-4">
    <div class="card-header bg-success bg-opacity-10">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 text-success">
                <i class="bi bi-check-circle-fill me-2"></i>Model Aktif: {{ $activeModel->name }}
            </h5>
            <span class="badge bg-success">AKTIF</span>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="text-muted small">Kernel</div>
                <div class="fw-medium text-uppercase">{{ $activeModel->kernel }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Akurasi</div>
                <div class="fw-medium text-success">{{ number_format($activeModel->accuracy * 100, 2) }}%</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">F1-Score</div>
                <div class="fw-medium">{{ number_format($activeModel->f1_score * 100, 2) }}%</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Training Date</div>
                <div class="fw-medium">{{ $activeModel->trained_at?->format('d M Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>
    Belum ada model aktif. Silakan latih model baru atau aktifkan model yang sudah ada.
</div>
@endif

<!-- Actions -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">Daftar Model</h5>
    <a href="{{ route('admin.ml-models.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Latih Model Baru
    </a>
</div>

<!-- Models Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Model</th>
                        <th>Kernel</th>
                        <th>Parameter</th>
                        <th class="text-center">Akurasi</th>
                        <th class="text-center">Precision</th>
                        <th class="text-center">Recall</th>
                        <th class="text-center">F1-Score</th>
                        <th>Tanggal Training</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($models as $model)
                    <tr class="{{ $model->is_active ? 'table-success' : '' }}">
                        <td>
                            <div class="fw-medium">{{ $model->name }}</div>
                            @if($model->description)
                            <small class="text-muted">{{ Str::limit($model->description, 50) }}</small>
                            @endif
                        </td>
                        <td><span class="badge bg-info text-uppercase">{{ $model->kernel }}</span></td>
                        <td>
                            <small>
                                C={{ $model->c_param }},
                                @if($model->gamma)γ={{ $model->gamma }}@endif
                                @if($model->degree && $model->kernel == 'poly'), deg={{ $model->degree }}@endif
                            </small>
                        </td>
                        <td class="text-center">
                            <span class="fw-medium {{ $model->accuracy >= 0.8 ? 'text-success' : ($model->accuracy >= 0.6 ? 'text-warning' : 'text-danger') }}">
                                {{ $model->accuracy ? number_format($model->accuracy * 100, 2) . '%' : '-' }}
                            </span>
                        </td>
                        <td class="text-center">{{ $model->precision ? number_format($model->precision * 100, 2) . '%' : '-' }}</td>
                        <td class="text-center">{{ $model->recall ? number_format($model->recall * 100, 2) . '%' : '-' }}</td>
                        <td class="text-center">{{ $model->f1_score ? number_format($model->f1_score * 100, 2) . '%' : '-' }}</td>
                        <td>{{ $model->trained_at?->format('d M Y H:i') ?? '-' }}</td>
                        <td>
                            @if($model->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.ml-models.show', $model) }}" class="btn btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(!$model->is_active)
                                <form action="{{ route('admin.ml-models.set-active', $model) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success" title="Aktifkan">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.ml-models.retrain', $model) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning" title="Re-train">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </form>
                                @if(!$model->is_active)
                                <form action="{{ route('admin.ml-models.destroy', $model) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus model ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="bi bi-cpu fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Belum ada model yang tersedia</p>
                            <a href="{{ route('admin.ml-models.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Latih Model Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($models->hasPages())
    <div class="card-footer">
        {{ $models->links() }}
    </div>
    @endif
</div>

<!-- Model Comparison Chart -->
@if($models->count() > 1)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Perbandingan Performa Model</h5>
    </div>
    <div class="card-body">
        <canvas id="modelComparisonChart" height="300"></canvas>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if($models->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('modelComparisonChart').getContext('2d');

    const modelsData = @json($models->take(5));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: modelsData.map(m => m.name),
            datasets: [
                {
                    label: 'Akurasi',
                    data: modelsData.map(m => m.accuracy ? m.accuracy * 100 : 0),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                },
                {
                    label: 'Precision',
                    data: modelsData.map(m => m.precision ? m.precision * 100 : 0),
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 1
                },
                {
                    label: 'Recall',
                    data: modelsData.map(m => m.recall ? m.recall * 100 : 0),
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    borderColor: 'rgb(255, 206, 86)',
                    borderWidth: 1
                },
                {
                    label: 'F1-Score',
                    data: modelsData.map(m => m.f1_score ? m.f1_score * 100 : 0),
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Persentase (%)'
                    }
                }
            }
        }
    });
});
</script>
@endif
@endpush
