@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2>Pengaturan Sistem</h2>
        <p class="text-muted">Log aktivitas pengguna sistem</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Menu Pengaturan</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('pengaturan.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-sliders-h me-2"></i> Pengaturan Umum
                    </a>
                    <a href="{{ route('pengaturan.users') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i> Manajemen Pengguna
                    </a>
                    <a href="{{ route('pengaturan.activity-log') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-history me-2"></i> Log Aktivitas
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <form action="{{ route('pengaturan.activity-log') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari aktivitas..." name="search" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="user" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Pengguna</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="module" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Modul</option>
                            <option value="auth" {{ request('module') == 'auth' ? 'selected' : '' }}>Autentikasi</option>
                            <option value="surat_masuk" {{ request('module') == 'surat_masuk' ? 'selected' : '' }}>Surat Masuk</option>
                            <option value="surat_keluar" {{ request('module') == 'surat_keluar' ? 'selected' : '' }}>Surat Keluar</option>
                            <option value="disposisi" {{ request('module') == 'disposisi' ? 'selected' : '' }}>Disposisi</option>
                            <option value="klasifikasi" {{ request('module') == 'klasifikasi' ? 'selected' : '' }}>Klasifikasi</option>
                            <option value="pengaturan" {{ request('module') == 'pengaturan' ? 'selected' : '' }}>Pengaturan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="action" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Aksi</option>
                            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                            <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Tambah</option>
                            <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Edit</option>
                            <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Hapus</option>
                            <option value="view" {{ request('action') == 'view' ? 'selected' : '' }}>Lihat</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Pengguna</th>
                                <th>Modul</th>
                                <th>Aksi</th>
                                <th>Keterangan</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $log->user->name ?? 'Sistem' }}</td>
                                <td>
                                    @if($log->module == 'auth')
                                    <span class="badge bg-primary">Autentikasi</span>
                                    @elseif($log->module == 'surat_masuk')
                                    <span class="badge bg-success">Surat Masuk</span>
                                    @elseif($log->module == 'surat_keluar')
                                    <span class="badge bg-info">Surat Keluar</span>
                                    @elseif($log->module == 'disposisi')
                                    <span class="badge bg-warning">Disposisi</span>
                                    @elseif($log->module == 'klasifikasi')
                                    <span class="badge bg-secondary">Klasifikasi</span>
                                    @elseif($log->module == 'pengaturan')
                                    <span class="badge bg-danger">Pengaturan</span>
                                    @else
                                    <span class="badge bg-dark">{{ $log->module }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->action == 'login')
                                    <span class="badge bg-success">Login</span>
                                    @elseif($log->action == 'logout')
                                    <span class="badge bg-secondary">Logout</span>
                                    @elseif($log->action == 'create')
                                    <span class="badge bg-primary">Tambah</span>
                                    @elseif($log->action == 'update')
                                    <span class="badge bg-warning">Edit</span>
                                    @elseif($log->action == 'delete')
                                    <span class="badge bg-danger">Hapus</span>
                                    @elseif($log->action == 'view')
                                    <span class="badge bg-info">Lihat</span>
                                    @else
                                    <span class="badge bg-dark">{{ $log->action }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->ip_address }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data log aktivitas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
