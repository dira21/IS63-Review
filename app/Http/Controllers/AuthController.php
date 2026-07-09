<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
     /**
     * showLoginForm() — Tampilkan halaman login
     * GET /login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }
 
    /**
     * login() — Proses autentikasi user
     * POST /login
     */
    public function login(Request $request)
    {
        // 1. Validasi input — konsisten dengan pola validasi di Bab 5
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);
 
        // 2. Coba autentikasi — Auth::attempt() otomatis
        //    mencocokkan password dengan hash di database
        $remember = $request->boolean('remember');
 
        if (Auth::attempt($credentials, $remember)) {
            // 3. Regenerasi session ID (cegah Session Fixation Attack)
            $request->session()->regenerate();
 
            // 4. Redirect ke halaman tujuan semula, atau ke dashboard
            return redirect()->intended(route('dashboard'))
                             ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }
 
        // 5. Login gagal — kembali ke form dengan pesan error
        //    old('email') memastikan email tidak perlu diketik ulang
        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
    }
 
    /**
     * logout() — Proses keluar dari sistem
     * POST /logout
     */
    public function logout(Request $request)
    {
        // 1. Logout user saat ini
        Auth::logout();
 
        // 2. Hapus semua data session
        $request->session()->invalidate();
 
        // 3. Regenerasi token CSRF (keamanan tambahan)
        $request->session()->regenerateToken();
 
        // 4. Redirect ke halaman login dengan flash message
        return redirect()->route('login')
                         ->with('success', 'Anda berhasil logout dari sistem.');
    }
}
