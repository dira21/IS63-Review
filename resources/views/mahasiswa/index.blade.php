{{-- resources/views/mahasiswa/index.blade.php --}}
@extends('layouts.app')
 
@section('title', 'Data Mahasiswa')
@section('page-title', 'Data Mahasiswa')
 
@section('page-action')
    <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm mr-1"></i> Tambah Mahasiswa
    </a>
@endsection
 
@section('content')
 
{{-- Form Filter --}}
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-secondary">
            <i class="fas fa-filter mr-2"></i>Filter Data
        </h6>
    </div>
    <div class="card-body py-3">
        <form method="GET" action="{{ route('mahasiswa.index') }}">
            <div class="form-row align-items-end">
                <div class="form-group col-md-4 mb-2">
                    <label class="small font-weight-bold">Cari Nama / NIM</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm" placeholder="Ketik nama atau NIM...">
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label class="small font-weight-bold">Status</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        @foreach(['aktif','cuti','lulus','dropout'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="small font-weight-bold">Program Studi</label>
                    <select name="prodi_id" class="form-control form-control-sm">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}"
                                {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label class="small font-weight-bold">Angkatan</label>
                    <input type="number" name="angkatan" value="{{ request('angkatan') }}"
                           class="form-control form-control-sm" placeholder="2022">
                </div>
                <div class="form-group col-md-1 mb-2">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            @if(request()->hasAny(['search','status','prodi_id','angkatan']))
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times mr-1"></i>Reset Filter
                </a>
            @endif
        </form>
    </div>
</div>
 
{{-- Tabel Data --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-users mr-2"></i>Daftar Mahasiswa
        </h6>
        <span class="text-muted small">{{ $mahasiswas->total() }} data ditemukan</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="12%">NIM</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th width="8%">Angkatan</th>
                        <th width="10%">Status</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $mhs)
                    <tr>
                        <td>{{ $mahasiswas->firstItem() + $loop->index }}</td>
                        <td><code>{{ $mhs->nim }}</code></td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($mhs->foto)
                                    <img src="{{ Storage::url($mhs->foto) }}"
                                         class="rounded-circle mr-2" width="32" height="32"
                                         style="object-fit:cover">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center
                                                justify-content-center mr-2 text-white"
                                         style="width:32px;height:32px;font-size:14px;flex-shrink:0">
                                        {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-weight-bold">{{ $mhs->nama }}</div>
                                    <small class="text-muted">{{ $mhs->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                        <td class="text-center">{{ $mhs->angkatan }}</td>
                        <td>
                            @php
                                $badgeColor = match($mhs->status) {
                                    'aktif'   => 'success',
                                    'cuti'    => 'warning',
                                    'lulus'   => 'info',
                                    'dropout' => 'danger',
                                    default   => 'secondary'
                                };
                            @endphp
                            <span class="badge badge-{{ $badgeColor }}">
                                {{ ucfirst($mhs->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('mahasiswa.show', $mhs) }}"
                               class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('mahasiswa.edit', $mhs) }}"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm"
                                    onclick="konfirmasiHapus({{ $mhs->id }}, '{{ $mhs->nama }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="form-hapus-{{ $mhs->id }}"
                                  action="{{ route('mahasiswa.destroy', $mhs) }}"
                                  method="POST" style="display:none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-search fa-2x mb-2 d-block"></i>
                            Tidak ada data mahasiswa yang sesuai filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">
                Menampilkan {{ $mahasiswas->firstItem() }}–{{ $mahasiswas->lastItem() }}
                dari {{ $mahasiswas->total() }} data
            </small>
            {{ $mahasiswas->links() }}
        </div>
    </div>
</div>
@endsection
 
@push('scripts')
<script>
function konfirmasiHapus(id, nama) {
    if (confirm('Hapus mahasiswa "' + nama + '"?
Semua data nilai mahasiswa ini juga akan terhapus!')) {
        document.getElementById('form-hapus-' + id).submit();
    }
}
</script>
@endpush
