@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="mb-4">Dashboard</h2>
        
        <div class="row">
            <!-- Surat Masuk Card -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card border-left-primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Surat Masuk</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSuratMasuk }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-envelope-open-text fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('surat-masuk.index') }}" class="small text-primary">Lihat Detail <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Surat Keluar Card -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card border-left-success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Surat Keluar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSuratKeluar }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-paper-plane fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('surat-keluar.index') }}" class="small text-success">Lihat Detail <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Disposisi Card -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card border-left-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Disposisi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDisposisi }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-share fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('disposisi.index') }}" class="small text-warning">Lihat Detail <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Pengguna Card -->
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card border-left-info h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Pengguna</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPengguna }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        @if(Auth::user()->role == 'admin')
                        <a href="{{ route('pengaturan.users') }}" class="small text-info">Lihat Detail <i class="fas fa-arrow-right"></i></a>
                        @else
                        <span class="small text-muted">Total Pengguna Sistem</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Surat Masuk Terbaru -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Surat Masuk Terbaru</h5>
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-list"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Tanggal</th>
                                <th>Perihal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suratMasukTerbaru as $surat)
                            <tr>
                                <td>{{ $surat->no_surat }}</td>
                                <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('surat-masuk.show', $surat) }}">
                                        {{ Str::limit($surat->perihal, 30) }}
                                    </a>
                                </td>
                                <td>
                                    @if($surat->status == 'belum_diproses')
                                    <span class="badge bg-danger">Belum Diproses</span>
                                    @elseif($surat->status == 'sedang_diproses')
                                    <span class="badge bg-warning">Sedang Diproses</span>
                                    @else
                                    <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data surat masuk</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Surat Keluar Terbaru -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Surat Keluar Terbaru</h5>
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-list"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Tanggal</th>
                                <th>Perihal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suratKeluarTerbaru as $surat)
                            <tr>
                                <td>{{ $surat->no_surat }}</td>
                                <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('surat-keluar.show', $surat) }}">
                                        {{ Str::limit($surat->perihal, 30) }}
                                    </a>
                                </td>
                                <td>
                                    @if($surat->status == 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                    @elseif($surat->status == 'menunggu_persetujuan')
                                    <span class="badge bg-warning">Menunggu Persetujuan</span>
                                    @elseif($surat->status == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                    @elseif($surat->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @else
                                    <span class="badge bg-primary">Dikirim</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data surat keluar</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Disposisi Terbaru -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Disposisi Terbaru untuk Anda</h5>
                <a href="{{ route('disposisi.index') }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-list"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Dari</th>
                                <th>Surat</th>
                                <th>Isi Disposisi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($disposisiTerbaru as $disposisi)
                            <tr>
                                <td>{{ $disposisi->tanggal_disposisi->format('d/m/Y') }}</td>
                                <td>{{ $disposisi->dari->name }}</td>
                                <td>
                                    <a href="{{ route('surat-masuk.show', $disposisi->suratMasuk) }}">
                                        {{ Str::limit($disposisi->suratMasuk->perihal, 30) }}
                                    </a>
                                </td>
                                <td>{{ Str::limit($disposisi->isi_disposisi, 30) }}</td>
                                <td>
                                    @if($disposisi->status == 'belum_dibaca')
                                    <span class="badge bg-danger">Belum Dibaca</span>
                                    @elseif($disposisi->status == 'dibaca')
                                    <span class="badge bg-warning">Dibaca</span>
                                    @elseif($disposisi->status == 'diproses')
                                    <span class="badge bg-info">Diproses</span>
                                    @else
                                    <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('disposisi.show', $disposisi) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada disposisi terbaru untuk Anda</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
