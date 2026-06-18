<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
 
// Dashboard (sementara, tanpa middleware auth — akan ditambah di Bab 7)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
 
// Route untuk modul lain akan ditambahkan di Bab 5
Route::resource('prodi', ProdiController::class);
Route::resource('mahasiswa', MahasiswaController::class);
Route::resource('nilai', NilaiController::class);