<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\LogAktivitas;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }
    
    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            // Log aktivitas login
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => 'Login ke sistem',
                'modul' => 'Auth',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
            
            return redirect()->intended('dashboard');
        }
        
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak valid.',
        ])->withInput($request->only('email', 'remember'));
    }
    
    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        // Log aktivitas logout
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Logout dari sistem',
            'modul' => 'Auth',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
    
    /**
     * Menampilkan halaman profil pengguna
     */
    public function showProfile()
    {
        return view('auth.profile', [
            'user' => Auth::user()
        ]);
    }
    
    /**
     * Update profil pengguna
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'jabatan' => 'required|string|max:255',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);
        
        // Verifikasi password saat ini jika ada perubahan password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini tidak sesuai'
                ]);
            }
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->jabatan = $request->jabatan;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        // Log aktivitas update profil
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aktivitas' => 'Update profil pengguna',
            'modul' => 'Auth',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
        
        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }
}
