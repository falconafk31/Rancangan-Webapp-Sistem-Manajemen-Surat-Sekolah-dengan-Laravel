@extends('layouts.app')

@section('title', 'Edit Surat Masuk')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Edit Surat Masuk</h2>
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('surat-masuk.update', $suratMasuk) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="no_surat" class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('no_surat') is-invalid @enderror" id="no_surat" name="no_surat" value="{{ old('no_surat', $suratMasuk->no_surat) }}" required>
                    @error('no_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="klasifikasi_id" class="form-label">Klasifikasi <span class="text-danger">*</span></label>
                    <select class="form-select @error('klasifikasi_id') is-invalid @enderror" id="klasifikasi_id" name="klasifikasi_id" required>
                        <option value="">-- Pilih Klasifikasi --</option>
                        @foreach($klasifikasi as $k)
                            <option value="{{ $k->id }}" {{ old('klasifikasi_id', $suratMasuk->klasifikasi_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->kode }} - {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('klasifikasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tanggal_surat" class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat->format('Y-m-d')) }}" required>
                    @error('tanggal_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="tanggal_terima" class="form-label">Tanggal Terima <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_terima') is-invalid @enderror" id="tanggal_terima" name="tanggal_terima" value="{{ old('tanggal_terima', $suratMasuk->tanggal_terima->format('Y-m-d')) }}" required>
                    @error('tanggal_terima')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="asal_surat" class="form-label">Asal Surat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('asal_surat') is-invalid @enderror" id="asal_surat" name="asal_surat" value="{{ old('asal_surat', $suratMasuk->asal_surat) }}" required>
                    @error('asal_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="tujuan" class="form-label">Tujuan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('tujuan') is-invalid @enderror" id="tujuan" name="tujuan" value="{{ old('tujuan', $suratMasuk->tujuan) }}" required>
                    @error('tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <label for="perihal" class="form-label">Perihal <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal" value="{{ old('perihal', $suratMasuk->perihal) }}" required>
                @error('perihal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="isi_ringkas" class="form-label">Isi Ringkas</label>
                <textarea class="form-control @error('isi_ringkas') is-invalid @enderror" id="isi_ringkas" name="isi_ringkas" rows="4">{{ old('isi_ringkas', $suratMasuk->isi_ringkas) }}</textarea>
                @error('isi_ringkas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="file_surat" class="form-label">File Surat (PDF/JPG/PNG, maks. 2MB)</label>
                @if($suratMasuk->file_path)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $suratMasuk->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-file me-1"></i> Lihat File Saat Ini
                        </a>
                    </div>
                @endif
                <input type="file" class="form-control @error('file_surat') is-invalid @enderror" id="file_surat" name="file_surat">
                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file</small>
                @error('file_surat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="lampiran" class="form-label">Tambah Lampiran (maks. 5 file)</label>
                @if($suratMasuk->lampiran->count() > 0)
                    <div class="mb-2">
                        <p class="mb-1">Lampiran saat ini:</p>
                        <ul class="list-group mb-2">
                            @foreach($suratMasuk->lampiran as $lampiran)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $lampiran->nama_file }}
                                    <a href="{{ asset('storage/' . $lampiran->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <input type="file" class="form-control @error('lampiran') is-invalid @enderror" id="lampiran" name="lampiran[]" multiple>
                <small class="text-muted">Anda dapat memilih beberapa file sekaligus untuk menambahkan lampiran baru</small>
                @error('lampiran')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="belum_diproses" {{ old('status', $suratMasuk->status) == 'belum_diproses' ? 'selected' : '' }}>Belum Diproses</option>
                    <option value="sedang_diproses" {{ old('status', $suratMasuk->status) == 'sedang_diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="selesai" {{ old('status', $suratMasuk->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="reset" class="btn btn-secondary me-md-2">
                    <i class="fas fa-undo me-1"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
