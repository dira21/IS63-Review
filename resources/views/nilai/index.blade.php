{{-- resources/views/nilai/index.blade.php --}}
@extends('layouts.app')
 
@section('title', 'Data Nilai')
@section('page-title', 'Data Nilai Mahasiswa')
 
@section('page-action')
    <a href="{{ route('nilai.create') }}" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm mr-1"></i> Tambah Nilai
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
        <form method="GET" action="{{ route('nilai.index') }}">
            <div class="form-row align-items-end">
                <div class="form-group col-md-4 mb-2">
                    <label class="small font-weight-bold">Mahasiswa</label>
                    <select name="mahasiswa_id" class="form-control form-control-sm">
                        <option value="">Semua Mahasiswa</option>
                        @foreach($mahasiswas as $mhs)
                            <option value="{{ $mhs->id }}"
                                {{ request('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                {{ $mhs->nim }} - {{ $mhs->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="small font-weight-bold">Matakuliah</label>
                    <select name="kode_mk" class="form-control form-control-sm">
                        <option value="">Semua Matakuliah</option>
                        @foreach($matakuliahs as $mk)
                            <option value="{{ $mk->kode_mk }}"
                                {{ request('kode_mk') == $mk->kode_mk ? 'selected' : '' }}>
                                {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label class="small font-weight-bold">Semester</label>
                    <select name="semester" class="form-control form-control-sm">
                        <option value="">Semua</option>
                        <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected':'' }}>Ganjil</option>
                        <option value="Genap"  {{ request('semester') == 'Genap'  ? 'selected':'' }}>Genap</option>
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label class="small font-weight-bold">Tahun Akademik</label>
                    <input type="number" name="tahun_akademik"
                           value="{{ request('tahun_akademik') }}"
                           class="form-control form-control-sm" placeholder="2023">
                </div>
                <div class="form-group col-md-1 mb-2">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            @if(request()->hasAny(['mahasiswa_id','kode_mk','semester','tahun_akademik']))
                <a href="{{ route('nilai.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times mr-1"></i>Reset Filter
                </a>
            @endif
        </form>
    </div>
</div>
 
{{-- Tabel Data Nilai --}}
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-chart-bar mr-2"></i>Daftar Nilai
        </h6>
        <span class="text-muted small">{{ $nilais->total() }} data ditemukan</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="4%">#</th>
                        <th>Mahasiswa</th>
                        <th>Program Studi</th>
                        <th>Matakuliah</th>
                        <th class="text-center" width="6%">SKS</th>
                        <th class="text-center" width="8%">Nilai</th>
                        <th class="text-center" width="7%">Grade</th>
                        <th width="8%">Semester</th>
                        <th width="6%">T.A.</th>
                        <th class="text-center" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $nilai)
                    <tr>
                        <td>{{ $nilais->firstItem() + $loop->index }}</td>
                        <td>
                            <a href="{{ route('mahasiswa.show', $nilai->mahasiswa) }}"
                               class="font-weight-bold">
                                {{ $nilai->mahasiswa->nama }}
                            </a>
                            <br><small class="text-muted"><code>{{ $nilai->mahasiswa->nim }}</code></small>
                        </td>
                        <td>
                            <small>{{ $nilai->mahasiswa->prodi->nama_prodi ?? '-' }}</small>
                        </td>
                        <td>
                            {{ $nilai->nama_mk }}
                            <br><small class="text-muted"><code>{{ $nilai->kode_mk }}</code></small>
                        </td>
                        <td class="text-center">{{ $nilai->sks }}</td>
                        <td class="text-center font-weight-bold">
                            {{ number_format($nilai->nilai_angka, 1) }}
                        </td>
                        <td class="text-center">
                            @php
                                $gc = match($nilai->nilai_huruf) {
                                    'A'  => 'success', 'AB' => 'primary',
                                    'B'  => 'info',    'BC' => 'secondary',
                                    'C'  => 'warning', default => 'danger'
                                };
                            @endphp
                            <span class="badge badge-{{ $gc }} badge-pill">{{ $nilai->nilai_huruf }}</span>
                        </td>
                        <td>{{ $nilai->semester }}</td>
                        <td class="text-center">{{ $nilai->tahun_akademik }}</td>
                        <td class="text-center">
                            <a href="{{ route('nilai.edit', $nilai) }}"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" title="Hapus"
                                    onclick="hapusNilai({{ $nilai->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="form-hapus-nilai-{{ $nilai->id }}"
                                  action="{{ route('nilai.destroy', $nilai) }}"
                                  method="POST" style="display:none">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            Tidak ada data nilai yang sesuai filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">
                Menampilkan {{ $nilais->firstItem() }}–{{ $nilais->lastItem() }}
                dari {{ $nilais->total() }} data
            </small>
            {{ $nilais->links() }}
        </div>
    </div>
</div>
 
@endsection
 
@push('scripts')
<script>
function hapusNilai(id) {
    if (confirm('Hapus nilai ini?\nAksi ini tidak bisa dibatalkan.')) {
        document.getElementById('form-hapus-nilai-' + id).submit();
    }
}
</script>
@endpush
