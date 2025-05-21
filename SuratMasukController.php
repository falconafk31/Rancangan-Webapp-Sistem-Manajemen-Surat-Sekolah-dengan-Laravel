<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\Klasifikasi;
use App\Models\Lampiran;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SuratMasuk::with(['klasifikasi', 'creator', 'lampiran']);
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_agenda', 'like', "%{$search}%")
                  ->orWhere('no_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('asal_surat', 'like', "%{$search}%");
            });
        }
        
        // Urutkan berdasarkan tanggal terbaru
        $query->orderBy('tanggal_terima', 'desc');
        
        $suratMasuk = $query->paginate(10);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat daftar surat masuk',
            'modul' => 'Surat Masuk',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('surat.masuk.index', compact('suratMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $klasifikasi = Klasifikasi::all();
        return view('surat.masuk.create', compact('klasifikasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'asal_surat' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'isi_ringkas' => 'nullable|string',
            'klasifikasi_id' => 'required|exists:klasifikasi,id',
            'status' => 'required|in:belum_diproses,sedang_diproses,selesai',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        // Generate nomor agenda
        $lastSurat = SuratMasuk::orderBy('id', 'desc')->first();
        $lastId = $lastSurat ? $lastSurat->id + 1 : 1;
        $no_agenda = 'SM-' . date('Y') . '-' . str_pad($lastId, 3, '0', STR_PAD_LEFT);
        
        // Upload file jika ada
        $file_path = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug($request->perihal) . '.' . $file->getClientOriginalExtension();
            $file_path = $file->storeAs('surat/masuk', $fileName, 'public');
        }
        
        // Simpan surat masuk
        $suratMasuk = SuratMasuk::create([
            'no_agenda' => $no_agenda,
            'no_surat' => $request->no_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_terima' => $request->tanggal_terima,
            'asal_surat' => $request->asal_surat,
            'tujuan' => $request->tujuan,
            'perihal' => $request->perihal,
            'isi_ringkas' => $request->isi_ringkas,
            'klasifikasi_id' => $request->klasifikasi_id,
            'file_path' => $file_path,
            'status' => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        
        // Simpan lampiran jika ada
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $fileName = time() . '_' . Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('lampiran/surat_masuk', $fileName, 'public');
                
                Lampiran::create([
                    'surat_masuk_id' => $suratMasuk->id,
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'ukuran_file' => $file->getSize(),
                    'tipe_file' => $file->getClientOriginalExtension(),
                    'created_by' => Auth::id(),
                ]);
            }
        }
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menambahkan surat masuk baru: ' . $request->no_surat,
            'modul' => 'Surat Masuk',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['klasifikasi', 'creator', 'updater', 'lampiran', 'disposisi.dari', 'disposisi.kepada']);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat detail surat masuk: ' . $suratMasuk->no_surat,
            'modul' => 'Surat Masuk',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('surat.masuk.show', compact('suratMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratMasuk $suratMasuk)
    {
        $klasifikasi = Klasifikasi::all();
        return view('surat.masuk.edit', compact('suratMasuk', 'klasifikasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $request->validate([
            'no_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'asal_surat' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'isi_ringkas' => 'nullable|string',
            'klasifikasi_id' => 'required|exists:klasifikasi,id',
            'status' => 'required|in:belum_diproses,sedang_diproses,selesai',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        // Upload file jika ada
        if ($request->hasFile('file_surat')) {
            // Hapus file lama jika ada
            if ($suratMasuk->file_path) {
                Storage::disk('public')->delete($suratMasuk->file_path);
            }
            
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug($request->perihal) . '.' . $file->getClientOriginalExtension();
            $file_path = $file->storeAs('surat/masuk', $fileName, 'public');
            
            $suratMasuk->file_path = $file_path;
        }
        
        // Update surat masuk
        $suratMasuk->update([
            'no_surat' => $request->no_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_terima' => $request->tanggal_terima,
            'asal_surat' => $request->asal_surat,
            'tujuan' => $request->tujuan,
            'perihal' => $request->perihal,
            'isi_ringkas' => $request->isi_ringkas,
            'klasifikasi_id' => $request->klasifikasi_id,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);
        
        // Simpan lampiran jika ada
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $fileName = time() . '_' . Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('lampiran/surat_masuk', $fileName, 'public');
                
                Lampiran::create([
                    'surat_masuk_id' => $suratMasuk->id,
                    'nama_file' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'ukuran_file' => $file->getSize(),
                    'tipe_file' => $file->getClientOriginalExtension(),
                    'created_by' => Auth::id(),
                ]);
            }
        }
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengubah surat masuk: ' . $request->no_surat,
            'modul' => 'Surat Masuk',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, SuratMasuk $suratMasuk)
    {
        // Hapus file surat jika ada
        if ($suratMasuk->file_path) {
            Storage::disk('public')->delete($suratMasuk->file_path);
        }
        
        // Hapus lampiran terkait
        foreach ($suratMasuk->lampiran as $lampiran) {
            Storage::disk('public')->delete($lampiran->file_path);
            $lampiran->delete();
        }
        
        // Hapus disposisi terkait
        $suratMasuk->disposisi()->delete();
        
        // Hapus surat masuk
        $suratMasuk->delete();
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus surat masuk: ' . $suratMasuk->no_surat,
            'modul' => 'Surat Masuk',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus');
    }
}
