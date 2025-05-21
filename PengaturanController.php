<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengaturanController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        // Ambil pengaturan dari database, jika belum ada buat baru
        $pengaturan = Pengaturan::first();
        
        if (!$pengaturan) {
            $pengaturan = Pengaturan::create([
                'nama_sekolah' => 'Nama Sekolah',
                'alamat' => 'Alamat Sekolah',
                'telepon' => '021-xxxxxxx',
                'email' => 'info@sekolah.sch.id',
                'website' => 'www.sekolah.sch.id',
                'format_nomor_surat' => '{kode}/{no}/{bulan}/{tahun}',
                'pimpinan_nama' => 'Nama Kepala Sekolah',
                'updated_by' => Auth::id(),
                'updated_at' => now(),
            ]);
        }
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat pengaturan sistem',
            'modul' => 'Pengaturan',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('pengaturan.index', compact('pengaturan'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|string|max:255',
            'format_nomor_surat' => 'required|string|max:255',
            'pimpinan_nama' => 'required|string|max:255',
            'pimpinan_nip' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kop_surat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Ambil pengaturan dari database
        $pengaturan = Pengaturan::first();
        
        // Upload logo jika ada
        if ($request->hasFile('logo')) {
            // Hapus file lama jika ada
            if ($pengaturan->logo) {
                Storage::disk('public')->delete($pengaturan->logo);
            }
            
            $file = $request->file('logo');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('pengaturan', $fileName, 'public');
            
            $pengaturan->logo = $filePath;
        }
        
        // Upload kop surat jika ada
        if ($request->hasFile('kop_surat')) {
            // Hapus file lama jika ada
            if ($pengaturan->kop_surat) {
                Storage::disk('public')->delete($pengaturan->kop_surat);
            }
            
            $file = $request->file('kop_surat');
            $fileName = 'kop_surat_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('pengaturan', $fileName, 'public');
            
            $pengaturan->kop_surat = $filePath;
        }
        
        // Update pengaturan
        $pengaturan->update([
            'nama_sekolah' => $request->nama_sekolah,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'website' => $request->website,
            'format_nomor_surat' => $request->format_nomor_surat,
            'pimpinan_nama' => $request->pimpinan_nama,
            'pimpinan_nip' => $request->pimpinan_nip,
            'updated_by' => Auth::id(),
            'updated_at' => now(),
        ]);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengubah pengaturan sistem',
            'modul' => 'Pengaturan',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan berhasil diperbarui');
    }
    
    /**
     * Display the user management page.
     */
    public function users()
    {
        $users = \App\Models\User::orderBy('name')->paginate(10);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat daftar pengguna',
            'modul' => 'Pengaturan',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('pengaturan.users', compact('users'));
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function createUser()
    {
        return view('pengaturan.user_create');
    }
    
    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,kepala_sekolah,staff,guru',
            'jabatan' => 'required|string|max:255',
        ]);
        
        // Buat user baru
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'jabatan' => $request->jabatan,
        ]);
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menambahkan pengguna baru: ' . $request->name,
            'modul' => 'Pengaturan',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('pengaturan.users')->with('success', 'Pengguna berhasil ditambahkan');
    }
    
    /**
     * Show the form for editing a user.
     */
    public function editUser(\App\Models\User $user)
    {
        return view('pengaturan.user_edit', compact('user'));
    }
    
    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,kepala_sekolah,staff,guru',
            'jabatan' => 'required|string|max:255',
        ]);
        
        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->jabatan = $request->jabatan;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Mengubah data pengguna: ' . $request->name,
            'modul' => 'Pengaturan',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('pengaturan.users')->with('success', 'Data pengguna berhasil diperbarui');
    }
    
    /**
     * Remove the specified user.
     */
    public function destroyUser(Request $request, \App\Models\User $user)
    {
        // Cek apakah user yang akan dihapus adalah user yang sedang login
        if ($user->id == Auth::id()) {
            return redirect()->route('pengaturan.users')->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan');
        }
        
        $userName = $user->name;
        
        // Hapus user
        $user->delete();
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Menghapus pengguna: ' . $userName,
            'modul' => 'Pengaturan',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('pengaturan.users')->with('success', 'Pengguna berhasil dihapus');
    }
    
    /**
     * Display the activity log page.
     */
    public function activityLog(Request $request)
    {
        $query = LogAktivitas::with('user');
        
        // Filter berdasarkan user
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter berdasarkan modul
        if ($request->has('modul') && !empty($request->modul)) {
            $query->where('modul', $request->modul);
        }
        
        // Filter berdasarkan tanggal
        if ($request->has('tanggal_mulai') && !empty($request->tanggal_mulai)) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }
        
        if ($request->has('tanggal_akhir') && !empty($request->tanggal_akhir)) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }
        
        // Urutkan berdasarkan waktu terbaru
        $query->orderBy('created_at', 'desc');
        
        $logs = $query->paginate(20);
        $users = \App\Models\User::orderBy('name')->get();
        $moduls = LogAktivitas::distinct('modul')->pluck('modul');
        
        // Log aktivitas
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Melihat log aktivitas',
            'modul' => 'Pengaturan',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return view('pengaturan.activity_log', compact('logs', 'users', 'moduls'));
    }
}
