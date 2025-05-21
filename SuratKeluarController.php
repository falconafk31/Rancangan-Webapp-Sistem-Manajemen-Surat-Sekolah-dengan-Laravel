<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\Klasifikasi;
use App\Models\Lampiran;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SuratKeluar::with(['klasifikasi', 'creator', 'lampiran']);
        
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
                  ->orWhere('tujuan', 'like', "%{$search}%");
            });
        }
        
        // Urutkan berdasarkan tanggal terbaru
        $query->orderBy('tanggal_surat', 'desc');
        
        $suratKeluar = $query->paginate(10);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat daftar surat keluar',
            'modul' => 'Surat Keluar',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('surat.keluar.index', compact('suratKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $klasifikasi = Klasifikasi::all();
        return view('surat.keluar.create', compact('klasifikasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_kirim' => 'nullable|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'isi_ringkas' => 'nullable|string',
            'klasifikasi_id' => 'required|exists:klasifikasi,id',
            'status' => 'required|in:draft,menunggu_persetujuan,disetujui,ditolak,dikirim',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        // Generate nomor agenda
        $lastSurat = SuratKeluar::orderBy('id', 'desc')->first();
        $lastId = $lastSurat ? $lastSurat->id + 1 : 1;
        $no_agenda = 'SK-' . date('Y') . '-' . str_pad($lastId, 3, '0', STR_PAD_LEFT);
        
        // Upload file jika ada
        $file_path = null;
        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug($request->perihal) . '.' . $file->getClientOriginalExtension();
            $file_path = $file->storeAs('surat/keluar', $fileName, 'public');
        }
        
        // Simpan surat keluar
        $suratKeluar = SuratKeluar::create([
            'no_agenda' => $no_agenda,
            'no_surat' => $request->no_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_kirim' => $request->tanggal_kirim,
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
                $filePath = $file->storeAs('lampiran/surat_keluar', $fileName, 'public');
                
                Lampiran::create([
                    'surat_keluar_id' => $suratKeluar->id,
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
            'aktivitas' => 'Menambahkan surat keluar baru: ' . $request->no_surat,
            'modul' => 'Surat Keluar',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['klasifikasi', 'creator', 'updater', 'lampiran']);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat detail surat keluar: ' . $suratKeluar->no_surat,
            'modul' => 'Surat Keluar',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('surat.keluar.show', compact('suratKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratKeluar $suratKeluar)
    {
        $klasifikasi = Klasifikasi::all();
        return view('surat.keluar.edit', compact('suratKeluar', 'klasifikasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        $request->validate([
            'no_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_kirim' => 'nullable|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'isi_ringkas' => 'nullable|string',
            'klasifikasi_id' => 'required|exists:klasifikasi,id',
            'status' => 'required|in:draft,menunggu_persetujuan,disetujui,ditolak,dikirim',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        
        // Upload file jika ada
        if ($request->hasFile('file_surat')) {
            // Hapus file lama jika ada
            if ($suratKeluar->file_path) {
                Storage::disk('public')->delete($suratKeluar->file_path);
            }
            
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::slug($request->perihal) . '.' . $file->getClientOriginalExtension();
            $file_path = $file->storeAs('surat/keluar', $fileName, 'public');
            
            $suratKeluar->file_path = $file_path;
        }
        
        // Update surat keluar
        $suratKeluar->update([
            'no_surat' => $request->no_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_kirim' => $request->tanggal_kirim,
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
                $filePath = $file->storeAs('lampiran/surat_keluar', $fileName, 'public');
                
                Lampiran::create([
                    'surat_keluar_id' => $suratKeluar->id,
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
            'aktivitas' => 'Mengubah surat keluar: ' . $request->no_surat,
            'modul' => 'Surat Keluar',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, SuratKeluar $suratKeluar)
    {
        // Hapus file surat jika ada
        if ($suratKeluar->file_path) {
            Storage::disk('public')->delete($suratKeluar->file_path);
        }
        
        // Hapus lampiran terkait
        foreach ($suratKeluar->lampiran as $lampiran) {
            Storage::disk('public')->delete($lampiran->file_path);
            $lampiran->delete();
        }
        
        // Hapus surat keluar
        $suratKeluar->delete();
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus surat keluar: ' . $suratKeluar->no_surat,
            'modul' => 'Surat Keluar',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil dihapus');
    }
}
