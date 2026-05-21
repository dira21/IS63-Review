{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
 
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
 
@section('content')
 
{{-- Baris Kartu Statistik --}}
<div class="row">
 
    {{-- Kartu: Total Prodi --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Program Studi
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalProdi }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-university fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- Kartu: Total Mahasiswa --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Mahasiswa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalMahasiswa }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- Kartu: Total Nilai --}}
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Data Nilai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalNilai }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
</div>
 
{{-- Tabel Mahasiswa Terbaru --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-users mr-2"></i>Mahasiswa Terbaru
        </h6>
        {{-- <a href="{{ route('mahasiswa.index') }}" class="btn btn-sm btn-primary">
            Lihat Semua
        </a> --}}
        <a href="#">
            Lihat Semua
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th>Angkatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswaTerbaru as $mhs)
                    <tr>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $mhs->nama }}</td>
                        <td>{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                        <td>{{ $mhs->
                         angkatan }}</td>
                        <td>
                            <span class="badge badge-{{
                                $mhs->status === 'aktif' ? 'success' :
                                ($mhs->status === 'cuti' ? 'warning' :
                                ($mhs->status === 'lulus' ? 'info' : 'danger'))
                            }}">
                                {{ ucfirst($mhs->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada data mahasiswa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
 
@endsection