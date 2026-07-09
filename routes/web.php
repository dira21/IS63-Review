<?php
// routes/web.php — VERSI FINAL
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\NilaiController;
 
// ============================================================
// ROUTE TAMU (Guest)
// Hanya bisa diakses jika BELUM login.
// Jika user yang sudah login mencoba akses /login,
// Laravel otomatis redirect ke dashboard.
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});
 
// ============================================================
// ROUTE TERLINDUNGI (Auth)
// Hanya bisa diakses jika SUDAH login.
// Jika belum login, otomatis redirect ke /login.
// ============================================================
Route::middleware('auth')->group(function () {
 
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
 
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
 
    // Modul Program Studi — 7 route: prodi.index s/d prodi.destroy
    Route::resource('prodi', ProdiController::class);
 
    // Modul Mahasiswa — 7 route: mahasiswa.index s/d mahasiswa.destroy
    Route::resource('mahasiswa', MahasiswaController::class);
 
    // Modul Nilai — 7 route: nilai.index s/d nilai.destroy
    Route::resource('nilai', NilaiController::class);
 
});