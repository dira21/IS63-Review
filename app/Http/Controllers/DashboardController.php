<?php
 
namespace App\Http\Controllers;
 
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;
 
class DashboardController extends Controller
{
    public function index()
    {
        $totalProdi      = Prodi::count();
        $totalMahasiswa  = Mahasiswa::count();
        $totalNilai      = Nilai::count();
        $mahasiswaTerbaru = Mahasiswa::with('prodi')
                                ->latest()
                                ->take(5)
                                ->get();
 
        return view('dashboard', compact(
            'totalProdi',
            'totalMahasiswa',
            'totalNilai',
            'mahasiswaTerbaru'
        ));
    }
}