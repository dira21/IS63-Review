{{-- resources/views/mahasiswa/show.blade.php --}}
@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp
 
@section('title', $mahasiswa->nama)
@section('page-title', 'Detail Mahasiswa')
 
@section('page-action')
    <div class="d-flex gap-2">
        <a href="{{ route('mahasiswa.edit', $mahasiswa) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit mr-1"></i> Edit
        </a>
        <a href="{{ route('nilai.create', ['mahasiswa_id' => $mahasiswa->id]) }}"
           class="btn btn-success btn-sm">
            <i class="fas fa-plus mr-1"></i> Tambah Nilai
        </a>
    </div>
@endsection
 
@section('content')
<div class="row">
 
    {{-- ===== KOLOM KIRI: PROFIL ===== --}}
    <div class="col-xl-4 col-lg-5">
 
        {{-- Kartu Foto & Nama --}}
        <div class="card shadow mb-4">
            <div class="card-body text-center py-4">
                @if($mahasiswa->foto)
                    <img src="{{ Storage::url($mahasiswa->foto) }}"
                         class="rounded-circle mb-3"
                         style="width:120px;height:120px;object-fit:cover;">
                @else
                    <div class="rounded-circle bg-gradient-primary d-inline-flex
                                align-items-center justify-content-center mb-3"
                         style="width:120px;height:120px;">
                        <span class="text-white" style="font-size:3rem;font-weight:700;">
                            {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                        </span>
                    </div>
                @endif
                <h5 class="font-weight-bold mb-1">{{ $mahasiswa->nama }}</h5>
                <p class="text-muted mb-2"><code>{{ $mahasiswa->nim }}</code></p>
                @php
                    $badgeColor = match($mahasiswa->status) {
                        'aktif'   => 'success',
                        'cuti'    => 'warning',
                        'lulus'   => 'info',
                        'dropout' => 'danger',
                        default   => 'secondary'
                    };
                @endphp
                <span class="badge badge-{{ $badgeColor }} badge-pill px-3 py-2">
                    {{ ucfirst($mahasiswa->status) }}
                </span>
            </div>
        </div>
 
        {{-- Kartu Informasi Detail --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Mahasiswa</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th width="40%"><i class="fas fa-university mr-1 text-muted"></i> Prodi</th>
                        <td>
                            <a href="{{ route('prodi.show', $mahasiswa->prodi) }}">
                                {{ $mahasiswa->prodi->nama_prodi ?? '-' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-layer-group mr-1 text-muted"></i> Jenjang</th>
                        <td>
                            <span class="badge badge-info">
                                {{ $mahasiswa->prodi->jenjang ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-calendar mr-1 text-muted"></i> Angkatan</th>
                        <td>{{ $mahasiswa->angkatan }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-envelope mr-1 text-muted"></i> Email</th>
                        <td><small>{{ $mahasiswa->email }}</small></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-phone mr-1 text-muted"></i> No. HP</th>
                        <td>{{ $mahasiswa->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-map-marker-alt mr-1 text-muted"></i> Alamat</th>
                        <td><small>{{ $mahasiswa->alamat ?? '-' }}</small></td>
                    </tr>
                </table>
            </div>
        </div>

        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary btn-block mb-4">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
    </div>
 
    {{-- ===== KOLOM KANAN: NILAI ===== --}}
    <div class="col-xl-8 col-lg-7">
 
        {{-- Kartu Statistik Nilai --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total MK
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $mahasiswa->nilais->count() }} MK
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Rata-rata Nilai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ number_format($mahasiswa->nilais->avg('nilai_angka') ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total SKS
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $mahasiswa->nilais->sum('sks') }} SKS
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
        {{-- Tabel Transkrip Nilai --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar mr-2"></i>Transkrip Nilai
                </h6>
                <a href="{{ route('nilai.create', ['mahasiswa_id' => $mahasiswa->id]) }}"
                   class="btn btn-success btn-sm">
                    <i class="fas fa-plus mr-1"></i> Tambah Nilai
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Kode MK</th>
                                <th>Nama Matakuliah</th>
                                <th class="text-center" width="6%">SKS</th>
                                <th class="text-center" width="8%">Nilai</th>
                                <th class="text-center" width="7%">Grade</th>
                                <th width="8%">Semester</th>
                                <th width="7%">T.A.</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswa->nilais as $nilai)
                            <tr>
                                <td><code>{{ $nilai->kode_mk }}</code></td>
                                <td>{{ $nilai->nama_mk }}</td>
                                <td class="text-center">{{ $nilai->sks }}</td>
                                <td class="text-center font-weight-bold">
                                    {{ number_format($nilai->nilai_angka, 1) }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $gc = match($nilai->nilai_huruf) {
                                            'A'  => 'success',
                                            'AB' => 'primary',
                                            'B'  => 'info',
                                            'BC' => 'secondary',
                                            'C'  => 'warning',
                                            default => 'danger'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $gc }}">
                                        {{ $nilai->nilai_huruf }}
                                    </span>
                                </td>
                                <td>{{ $nilai->semester }}</td>
                                <td class="text-center">{{ $nilai->tahun_akademik }}</td>
                                <td class="text-center">
                                    <a href="{{ route('nilai.edit', $nilai) }}"
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm"
                                            title="Hapus"
                                            onclick="hapusNilai({{ $nilai->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="form-hapus-nilai-{{ $nilai->id }}"
                                          action="{{ route('nilai.destroy', $nilai) }}"
                                          method="POST" style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">
                                    Belum ada data nilai. Klik "Tambah Nilai" untuk menambahkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
 
    </div>
</div>
@endsection
 
@push('scripts')
<script>
function hapusNilai(id) {
    if (confirm('Hapus nilai ini? Aksi tidak bisa dibatalkan.')) {
        document.getElementById('form-hapus-nilai-' + id).submit();
    }
}
</script>
@endpush
