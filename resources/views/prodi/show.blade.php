{{-- resources/views/prodi/show.blade.php --}}
@extends('layouts.app')
 
@section('title', $prodi->nama_prodi)
@section('page-title', 'Detail Program Studi')
 
@section('page-action')
    <a href="{{ route('prodi.edit', $prodi) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit mr-1"></i> Edit Prodi
    </a>
@endsection
 
@section('content')
<div class="row">
 
    {{-- Info Prodi --}}
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-university mr-2"></i>Informasi Prodi
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th width="40%">Kode</th><td>{{ $prodi->kode_prodi }}</td></tr>
                    <tr><th>Nama Prodi</th><td>{{ $prodi->nama_prodi }}</td></tr>
                    <tr>
                        <th>Jenjang</th>
                        <td><span class="badge badge-info">{{ $prodi->jenjang }}</span></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge badge-{{ $prodi->status === 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($prodi->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Mahasiswa</th>
                        <td><strong>{{ $mahasiswas->total() }}</strong> orang</td>
                    </tr>
                </table>
                <hr>
                <a href="{{ route('prodi.index') }}" class="btn btn-secondary btn-block btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
 
    {{-- Tabel Mahasiswa di Prodi Ini --}}
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Mahasiswa — {{ $prodi->nama_prodi }}
                </h6>
                <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Mahasiswa
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th class="text-center">Angkatan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswas as $mhs)
                            <tr>
                                <td><code>{{ $mhs->nim }}</code></td>
                                <td>
                                    <a href="{{ route('mahasiswa.show', $mhs) }}">
                                        {{ $mhs->nama }}
                                    </a>
                                </td>
                                <td class="text-center">{{ $mhs->angkatan }}</td>
                                <td class="text-center">
                                    @php
                                        $bc = match($mhs->status) {
                                            'aktif'   => 'success',
                                            'cuti'    => 'warning',
                                            'lulus'   => 'info',
                                            'dropout' => 'danger',
                                            default   => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $bc }}">{{ ucfirst($mhs->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('mahasiswa.show', $mhs) }}"
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    Belum ada mahasiswa di prodi ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Menampilkan {{ $mahasiswas->firstItem() ?? 0 }}–{{ $mahasiswas->lastItem() ?? 0 }}
                        dari {{ $mahasiswas->total() }} mahasiswa
                    </small>
                    {{ $mahasiswas->links() }}
                </div>
            </div>
        </div>
    </div>
 
</div>
@endsection
