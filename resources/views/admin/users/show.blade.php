@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Detail User</h4>
        <p class="text-muted mb-0">Informasi detail pengguna sistem</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Edit
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-person-circle me-2"></i>Informasi User</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted" width="30%">Nama Lengkap</td>
                        <td class="fw-semibold">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Role</td>
                        <td>
                            @if($user->role->name === 'admin')
                                <span class="badge bg-danger">Administrator</span>
                            @else
                                <span class="badge bg-info">Guru</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email Verified</td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ $user->email_verified_at->format('d M Y H:i') }}
                                </span>
                            @else
                                <span class="text-muted">Belum diverifikasi</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Terdaftar Pada</td>
                        <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Terakhir Diperbarui</td>
                        <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="avatar-lg mx-auto mb-3" style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <span class="text-white fs-1">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-3">{{ $user->email }}</p>

                @if($user->role->name === 'admin')
                    <span class="badge bg-danger px-3 py-2 fs-6">
                        <i class="bi bi-shield-fill me-1"></i>Administrator
                    </span>
                @else
                    <span class="badge bg-info px-3 py-2 fs-6">
                        <i class="bi bi-person-workspace me-1"></i>Guru
                    </span>
                @endif
            </div>
        </div>

        @if($user->id !== auth()->id())
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-gear me-2"></i>Aksi</h6>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-trash me-2"></i>Hapus User
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
