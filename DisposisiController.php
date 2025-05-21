<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Disposisi::with(['suratMasuk', 'dari', 'kepada']);
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan user
        if ($request->has('user') && $request->user == 'saya') {
            $query->where('kepada_user_id', Auth::id());
        }
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('suratMasuk', function($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }
        
        // Urutkan berdasarkan tanggal terbaru
        $query->orderBy('tanggal_disposisi', 'desc');
        
        $disposisi = $query->paginate(10);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat daftar disposisi',
            'modul' => 'Disposisi',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('disposisi.index', compact('disposisi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $suratMasuk = null;
        if ($request->has('surat_masuk_id')) {
            $suratMasuk = SuratMasuk::findOrFail($request->surat_masuk_id);
        }
        
        $users = User::where('id', '!=', Auth::id())->get();
        
        return view('disposisi.create', compact('suratMasuk', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'surat_masuk_id' => 'required|exists:surat_masuk,id',
            'kepada_user_id' => 'required|exists:users,id',
            'tanggal_disposisi' => 'required|date',
            'isi_disposisi' => 'required|string',
            'sifat' => 'required|in:biasa,segera,sangat_segera,rahasia',
            'batas_waktu' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);
        
        // Simpan disposisi
        $disposisi = Disposisi::create([
            'surat_masuk_id' => $request->surat_masuk_id,
            'dari_user_id' => Auth::id(),
            'kepada_user_id' => $request->kepada_user_id,
            'tanggal_disposisi' => $request->tanggal_disposisi,
            'isi_disposisi' => $request->isi_disposisi,
            'sifat' => $request->sifat,
            'batas_waktu' => $request->batas_waktu,
            'status' => 'belum_dibaca',
            'catatan' => $request->catatan,
        ]);
        
        // Update status surat masuk
        $suratMasuk = SuratMasuk::find($request->surat_masuk_id);
        if ($suratMasuk->status == 'belum_diproses') {
            $suratMasuk->update([
                'status' => 'sedang_diproses',
                'updated_by' => Auth::id(),
            ]);
        }
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Membuat disposisi baru untuk surat: ' . $suratMasuk->no_surat,
            'modul' => 'Disposisi',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Disposisi $disposisi)
    {
        $disposisi->load(['suratMasuk', 'dari', 'kepada']);
        
        // Jika user adalah penerima disposisi dan status masih belum_dibaca, update menjadi dibaca
        if (Auth::id() == $disposisi->kepada_user_id && $disposisi->status == 'belum_dibaca') {
            $disposisi->update([
                'status' => 'dibaca'
            ]);
        }
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat detail disposisi untuk surat: ' . $disposisi->suratMasuk->no_surat,
            'modul' => 'Disposisi',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('disposisi.show', compact('disposisi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Disposisi $disposisi)
    {
        // Hanya pembuat disposisi yang bisa mengedit
        if (Auth::id() != $disposisi->dari_user_id) {
            return redirect()->route('disposisi.index')->with('error', 'Anda tidak memiliki akses untuk mengedit disposisi ini');
        }
        
        $disposisi->load('suratMasuk');
        $users = User::where('id', '!=', Auth::id())->get();
        
        return view('disposisi.edit', compact('disposisi', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Disposisi $disposisi)
    {
        // Hanya pembuat disposisi yang bisa mengedit
        if (Auth::id() != $disposisi->dari_user_id) {
            return redirect()->route('disposisi.index')->with('error', 'Anda tidak memiliki akses untuk mengedit disposisi ini');
        }
        
        $request->validate([
            'kepada_user_id' => 'required|exists:users,id',
            'tanggal_disposisi' => 'required|date',
            'isi_disposisi' => 'required|string',
            'sifat' => 'required|in:biasa,segera,sangat_segera,rahasia',
            'batas_waktu' => 'nullable|date',
            'catatan' => 'nullable|string',
        ]);
        
        // Update disposisi
        $disposisi->update([
            'kepada_user_id' => $request->kepada_user_id,
            'tanggal_disposisi' => $request->tanggal_disposisi,
            'isi_disposisi' => $request->isi_disposisi,
            'sifat' => $request->sifat,
            'batas_waktu' => $request->batas_waktu,
            'status' => 'belum_dibaca', // Reset status karena penerima berubah
            'catatan' => $request->catatan,
        ]);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengubah disposisi untuk surat: ' . $disposisi->suratMasuk->no_surat,
            'modul' => 'Disposisi',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Disposisi $disposisi)
    {
        // Hanya pembuat disposisi yang bisa menghapus
        if (Auth::id() != $disposisi->dari_user_id) {
            return redirect()->route('disposisi.index')->with('error', 'Anda tidak memiliki akses untuk menghapus disposisi ini');
        }
        
        $suratNo = $disposisi->suratMasuk->no_surat;
        
        // Hapus disposisi
        $disposisi->delete();
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus disposisi untuk surat: ' . $suratNo,
            'modul' => 'Disposisi',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dihapus');
    }
    
    /**
     * Update status disposisi
     */
    public function updateStatus(Request $request, Disposisi $disposisi)
    {
        // Hanya penerima disposisi yang bisa mengubah status
        if (Auth::id() != $disposisi->kepada_user_id) {
            return redirect()->route('disposisi.index')->with('error', 'Anda tidak memiliki akses untuk mengubah status disposisi ini');
        }
        
        $request->validate([
            'status' => 'required|in:dibaca,diproses,selesai',
        ]);
        
        // Update status disposisi
        $disposisi->update([
            'status' => $request->status,
        ]);
        
        // Jika status disposisi selesai, update status surat masuk jika semua disposisi selesai
        if ($request->status == 'selesai') {
            $suratMasuk = $disposisi->suratMasuk;
            $allDisposisiSelesai = $suratMasuk->disposisi()->where('status', '!=', 'selesai')->count() == 0;
            
            if ($allDisposisiSelesai) {
                $suratMasuk->update([
                    'status' => 'selesai',
                    'updated_by' => Auth::id(),
                ]);
            }
        }
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengubah status disposisi menjadi ' . $request->status . ' untuk surat: ' . $disposisi->suratMasuk->no_surat,
            'modul' => 'Disposisi',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('disposisi.show', $disposisi)->with('success', 'Status disposisi berhasil diperbarui');
    }
}
