@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detail Surat Masuk</h2>
            <div>
                <a href="{{ route('surat-masuk.edit', $suratMasuk) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informasi Surat</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Nomor Agenda</th>
                        <td>{{ $suratMasuk->no_agenda }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Surat</th>
                        <td>{{ $suratMasuk->no_surat }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Surat</th>
                        <td>{{ $suratMasuk->tanggal_surat->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Terima</th>
                        <td>{{ $suratMasuk->tanggal_terima->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Asal Surat</th>
                        <td>{{ $suratMasuk->asal_surat }}</td>
                    </tr>
                    <tr>
                        <th>Tujuan</th>
                        <td>{{ $suratMasuk->tujuan }}</td>
                    </tr>
                    <tr>
                        <th>Perihal</th>
                        <td>{{ $suratMasuk->perihal }}</td>
                    </tr>
                    <tr>
                        <th>Klasifikasi</th>
                        <td>{{ $suratMasuk->klasifikasi->kode }} - {{ $suratMasuk->klasifikasi->nama }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($suratMasuk->status == 'belum_diproses')
                            <span class="badge bg-danger">Belum Diproses</span>
                            @elseif($suratMasuk->status == 'sedang_diproses')
                            <span class="badge bg-warning">Sedang Diproses</span>
                            @else
                            <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Isi Ringkas</th>
                        <td>{{ $suratMasuk->isi_ringkas ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat Oleh</th>
                        <td>{{ $suratMasuk->creator->name }} ({{ $suratMasuk->created_at->format('d/m/Y H:i') }})</td>
                    </tr>
                    <tr>
                        <th>Diperbarui Oleh</th>
                        <td>{{ $suratMasuk->updater->name }} ({{ $suratMasuk->updated_at->format('d/m/Y H:i') }})</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($suratMasuk->file_path)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">File Surat</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    @php
                        $extension = pathinfo(storage_path('app/public/' . $suratMasuk->file_path), PATHINFO_EXTENSION);
                    @endphp
                    
                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $suratMasuk->file_path) }}" class="img-fluid mb-3" alt="Surat">
                    @endif
                    
                    <a href="{{ asset('storage/' . $suratMasuk->file_path) }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-file me-1"></i> Lihat File
                    </a>
                    
                    <a href="{{ asset('storage/' . $suratMasuk->file_path) }}" class="btn btn-success" download>
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                </div>
            </div>
        </div>
        @endif
        
        @if($suratMasuk->lampiran->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Lampiran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama File</th>
                                <th>Tipe</th>
                                <th>Ukuran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suratMasuk->lampiran as $lampiran)
                            <tr>
                                <td>{{ $lampiran->nama_file }}</td>
                                <td>{{ strtoupper($lampiran->tipe_file) }}</td>
                                <td>{{ number_format($lampiran->ukuran_file / 1024, 2) }} KB</td>
                                <td>
                                    <a href="{{ asset('storage/' . $lampiran->file_path) }}" class="btn btn-sm btn-primary" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ asset('storage/' . $lampiran->file_path) }}" class="btn btn-sm btn-success" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Disposisi</h5>
                <a href="{{ route('disposisi.create', ['surat_masuk_id' => $suratMasuk->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Disposisi
                </a>
            </div>
            <div class="card-body">
                @if($suratMasuk->disposisi->count() > 0)
                    <div class="list-group">
                        @foreach($suratMasuk->disposisi as $disposisi)
                            <a href="{{ route('disposisi.show', $disposisi) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Dari: {{ $disposisi->dari->name }}</h6>
                                    <small>{{ $disposisi->tanggal_disposisi->format('d/m/Y') }}</small>
                                </div>
                                <p class="mb-1">Kepada: {{ $disposisi->kepada->name }}</p>
                                <small>
                                    @if($disposisi->status == 'belum_dibaca')
                                    <span class="badge bg-danger">Belum Dibaca</span>
                                    @elseif($disposisi->status == 'dibaca')
                                    <span class="badge bg-warning">Dibaca</span>
                                    @elseif($disposisi->status == 'diproses')
                                    <span class="badge bg-info">Diproses</span>
                                    @else
                                    <span class="badge bg-success">Selesai</span>
                                    @endif
                                </small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted my-3">Belum ada disposisi</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
