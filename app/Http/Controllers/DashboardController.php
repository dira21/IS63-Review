<?php
// app/Http/Controllers/DashboardController.php
 
namespace App\Http\Controllers;
 
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\Nilai;
 
class DashboardController extends Controller
{
    public function index()
    {
        $totalProdi      = Prodi::count();
        $totalMahasiswa  = Mahasiswa::count();
        $totalNilai      = Nilai::count();
        $mahasiswaAktif  = Mahasiswa::where('status', 'aktif')->count();
 
        $mahasiswaTerbaru = Mahasiswa::with('prodi')
                                     ->latest()
                                     ->take(5)
                                     ->get();
 
        $statistikProdi = Prodi::withCount('mahasiswas')
                               ->orderByDesc('mahasiswas_count')
                               ->get();
 
        return view('dashboard', compact(
            'totalProdi',
            'totalMahasiswa',
            'totalNilai',
            'mahasiswaAktif',
            'mahasiswaTerbaru',
            'statistikProdi'
        ));
    }
}