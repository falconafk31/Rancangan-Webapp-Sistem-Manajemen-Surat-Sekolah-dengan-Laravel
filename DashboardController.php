<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard
     */
    public function index()
    {
        // Hitung jumlah data untuk statistik
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();
        $totalDisposisi = Disposisi::count();
        $totalPengguna = User::count();
        
        // Ambil data surat terbaru
        $suratMasukTerbaru = SuratMasuk::with(['klasifikasi', 'creator'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $suratKeluarTerbaru = SuratKeluar::with(['klasifikasi', 'creator'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Ambil disposisi terbaru untuk user saat ini
        $disposisiTerbaru = Disposisi::with(['suratMasuk', 'dari', 'kepada'])
            ->where('kepada_user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('dashboard', compact(
            'totalSuratMasuk', 
            'totalSuratKeluar', 
            'totalDisposisi', 
            'totalPengguna',
            'suratMasukTerbaru',
            'suratKeluarTerbaru',
            'disposisiTerbaru'
        ));
    }
}
