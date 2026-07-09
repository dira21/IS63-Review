@extends('layouts.app')
 
@section('title', 'Program Studi')
@section('page-title', 'Data Program Studi')
 
@section('page-action')
    <a href="{{ route('prodi.create') }}" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Prodi
    </a>
@endsection
 
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-university mr-2"></i>Daftar Program Studi
        </h6>
        <span class="badge badge-primary badge-pill">
            {{ $prodis->total() }} Total
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode Prodi</th>
                        <th>Nama Program Studi</th>
                        <th width="8%">Jenjang</th>
                        <th width="15%">Jumlah Mahasiswa</th>
                        <th width="10%">Status</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prodis as $prodi)
                    <tr>
                        <td>{{ $prodis->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $prodi->kode_prodi }}</strong></td>
                        <td>{{ $prodi->nama_prodi }}</td>
                        <td>
                            <span class="badge badge-{{ $prodi->jenjang === 'S2' || $prodi->jenjang === 'S3' ? 'warning' : 'info' }}">
                                {{ $prodi->jenjang }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('prodi.show', $prodi) }}"
                               class="text-primary font-weight-bold">
                                {{ $prodi->mahasiswas_count }} mahasiswa
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-{{ $prodi->status === 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($prodi->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('prodi.show', $prodi) }}"
                               class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('prodi.edit', $prodi) }}"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm"
                                    title="Hapus"
                                    onclick="konfirmasiHapus({{ $prodi->id }}, '{{ $prodi->nama_prodi }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                            {{-- Form hapus tersembunyi --}}
                            <form id="form-hapus-{{ $prodi->id }}"
                                  action="{{ route('prodi.destroy', $prodi) }}"
                                  method="POST" style="display:none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            Belum ada data program studi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
 
        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center mt-3">
            <small class="text-muted">
                Menampilkan {{ $prodis->firstItem() }}–{{ $prodis->lastItem() }}
                dari {{ $prodis->total() }} data
            </small>
            {{ $prodis->links() }}
        </div>
    </div>
</div>
@endsection
 
@push('scripts')
<script>
function konfirmasiHapus(id, nama) {
    if (confirm('Hapus program studi "' + nama + '"?
Aksi ini tidak bisa dibatalkan.')) {
        document.getElementById('form-hapus-' + id).submit();
    }
}

</script>
@endpush