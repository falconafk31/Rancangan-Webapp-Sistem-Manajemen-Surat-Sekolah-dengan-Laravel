@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2>Pengaturan Sistem</h2>
        <p class="text-muted">Manajemen pengguna sistem</p>
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
                    <a href="{{ route('pengaturan.users') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-users me-2"></i> Manajemen Pengguna
                    </a>
                    <a href="{{ route('pengaturan.activity-log') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-history me-2"></i> Log Aktivitas
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Pengguna</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Pengguna
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jabatan</th>
                                <th>Peran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->jabatan }}</td>
                                <td>
                                    @if($user->role == 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                    @elseif($user->role == 'kepala_sekolah')
                                    <span class="badge bg-primary">Kepala Sekolah</span>
                                    @elseif($user->role == 'wakil_kepala_sekolah')
                                    <span class="badge bg-info">Wakil Kepala Sekolah</span>
                                    @elseif($user->role == 'guru')
                                    <span class="badge bg-success">Guru</span>
                                    @elseif($user->role == 'staff')
                                    <span class="badge bg-secondary">Staff</span>
                                    @else
                                    <span class="badge bg-warning">{{ $user->role }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if(Auth::id() != $user->id)
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus" 
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) { document.getElementById('delete-form-{{ $user->id }}').submit(); }">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('pengaturan.users.destroy', $user) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Edit User Modal -->
                            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('pengaturan.users.update', $user) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit Pengguna</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="edit_name{{ $user->id }}" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="edit_name{{ $user->id }}" name="name" value="{{ $user->name }}" required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="edit_email{{ $user->id }}" class="form-label">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" id="edit_email{{ $user->id }}" name="email" value="{{ $user->email }}" required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="edit_jabatan{{ $user->id }}" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="edit_jabatan{{ $user->id }}" name="jabatan" value="{{ $user->jabatan }}" required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="edit_role{{ $user->id }}" class="form-label">Peran <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="edit_role{{ $user->id }}" name="role" required>
                                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                        <option value="kepala_sekolah" {{ $user->role == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                                        <option value="wakil_kepala_sekolah" {{ $user->role == 'wakil_kepala_sekolah' ? 'selected' : '' }}>Wakil Kepala Sekolah</option>
                                                        <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                                                        <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="edit_is_active{{ $user->id }}" class="form-label">Status <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="edit_is_active{{ $user->id }}" name="is_active" required>
                                                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                                                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label for="edit_password{{ $user->id }}" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="edit_password{{ $user->id }}" name="password">
                                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                                </div>
                                            </div>
                                            
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data pengguna</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pengaturan.users.store') }}" method="POST">
                @csrf
                
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Peran <span class="text-danger">*</span></label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">-- Pilih Peran --</option>
                            <option value="admin">Admin</option>
                            <option value="kepala_sekolah">Kepala Sekolah</option>
                            <option value="wakil_kepala_sekolah">Wakil Kepala Sekolah</option>
                            <option value="guru">Guru</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
