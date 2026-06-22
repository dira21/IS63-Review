<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\NilaiController;

// ─── Dashboard ───────────────────────────────────────────────
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ─── Modul Program Studi ──────────────────────────────────────
// Menghasilkan 7 route: prodi.index, prodi.create, prodi.store,
//                       prodi.show, prodi.edit, prodi.update, prodi.destroy
Route::resource('prodi', ProdiController::class);

// ─── Modul Mahasiswa ──────────────────────────────────────────
Route::resource('mahasiswa', MahasiswaController::class);

// ─── Modul Nilai ──────────────────────────────────────────────
Route::resource('nilai', NilaiController::class);