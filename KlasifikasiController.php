<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KlasifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Klasifikasi::query();
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }
        
        // Urutkan berdasarkan kode
        $query->orderBy('kode', 'asc');
        
        $klasifikasi = $query->paginate(10);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat daftar klasifikasi surat',
            'modul' => 'Klasifikasi',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('klasifikasi.index', compact('klasifikasi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('klasifikasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:klasifikasi',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        // Simpan klasifikasi
        Klasifikasi::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menambahkan klasifikasi baru: ' . $request->kode . ' - ' . $request->nama,
            'modul' => 'Klasifikasi',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Klasifikasi $klasifikasi)
    {
        // Hitung jumlah surat yang menggunakan klasifikasi ini
        $jumlahSuratMasuk = $klasifikasi->suratMasuk()->count();
        $jumlahSuratKeluar = $klasifikasi->suratKeluar()->count();
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat detail klasifikasi: ' . $klasifikasi->kode . ' - ' . $klasifikasi->nama,
            'modul' => 'Klasifikasi',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('klasifikasi.show', compact('klasifikasi', 'jumlahSuratMasuk', 'jumlahSuratKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Klasifikasi $klasifikasi)
    {
        return view('klasifikasi.edit', compact('klasifikasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Klasifikasi $klasifikasi)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:klasifikasi,kode,' . $klasifikasi->id,
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        // Update klasifikasi
        $klasifikasi->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
        ]);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengubah klasifikasi: ' . $request->kode . ' - ' . $request->nama,
            'modul' => 'Klasifikasi',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Klasifikasi $klasifikasi)
    {
        // Cek apakah klasifikasi digunakan oleh surat
        $jumlahSuratMasuk = $klasifikasi->suratMasuk()->count();
        $jumlahSuratKeluar = $klasifikasi->suratKeluar()->count();
        
        if ($jumlahSuratMasuk > 0 || $jumlahSuratKeluar > 0) {
            return redirect()->route('klasifikasi.index')->with('error', 'Klasifikasi tidak dapat dihapus karena masih digunakan oleh surat');
        }
        
        // Hapus klasifikasi
        $klasifikasi->delete();
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus klasifikasi: ' . $klasifikasi->kode . ' - ' . $klasifikasi->nama,
            'modul' => 'Klasifikasi',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('klasifikasi.index')->with('success', 'Klasifikasi berhasil dihapus');
    }
}
