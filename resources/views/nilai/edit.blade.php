{{-- resources/views/nilai/edit.blade.php --}}
@extends('layouts.app')
 
@section('title', 'Edit Nilai')
@section('page-title', 'Edit Data Nilai')
 
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-warning">
            <i class="fas fa-edit mr-2"></i>
            Edit Nilai: {{ $nilai->mahasiswa->nama }} &mdash; {{ $nilai->nama_mk }}
        </h6>
    </div>
    <div class="card-body">
 
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif
 
        {{-- Info banner mahasiswa yang nilainya sedang diedit --}}
        <div class="alert alert-info py-2">
            <i class="fas fa-info-circle mr-1"></i>
            Mengedit nilai <strong>{{ $nilai->nama_mk }}</strong>
            milik <strong>{{ $nilai->mahasiswa->nama }}</strong>
            (<code>{{ $nilai->mahasiswa->nim }}</code>)
        </div>
 
        <form action="{{ route('nilai.update', $nilai) }}" method="POST">
            @csrf
            @method('PUT')
 
            {{-- Pilih Mahasiswa --}}
            <div class="form-group">
                <label>Mahasiswa <span class="text-danger">*</span></label>
                <select name="mahasiswa_id" class="form-control">
                    @foreach($mahasiswas as $mhs)
                        <option value="{{ $mhs->id }}"
                            {{ old('mahasiswa_id', $nilai->mahasiswa_id) == $mhs->id
                                ? 'selected' : '' }}>
                            {{ $mhs->nim }} - {{ $mhs->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
 
            {{-- Pilih Matakuliah --}}
            <div class="form-group">
                <label>Matakuliah <span class="text-danger">*</span></label>
                <select name="kode_mk" id="selectMkEdit" class="form-control">
                    @foreach($matakuliahs as $mk)
                        <option value="{{ $mk['kode'] }}"
                                data-nama="{{ $mk['nama'] }}"
                                data-sks="{{ $mk['sks'] }}"
                                {{ old('kode_mk', $nilai->kode_mk) == $mk['kode']
                                    ? 'selected' : '' }}>
                            [{{ $mk['kode'] }}] {{ $mk['nama'] }} — {{ $mk['sks'] }} SKS
                        </option>
                    @endforeach
                </select>
            </div>
 
            <input type="hidden" name="nama_mk" id="hiddenNamaMkEdit"
                   value="{{ old('nama_mk', $nilai->nama_mk) }}">
            <input type="hidden" name="sks" id="hiddenSksEdit"
                   value="{{ old('sks', $nilai->sks) }}">
 
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Nilai Angka <span class="text-danger">*</span></label>
                    <input type="number" name="nilai_angka" id="inputNilaiEdit"
                           value="{{ old('nilai_angka', $nilai->nilai_angka) }}"
                           class="form-control" min="0" max="100" step="0.01">
                </div>
                <div class="form-group col-md-4">
                    <label>Grade <small class="text-muted">(otomatis)</small></label>
                    <input type="text" id="previewGradeEdit"
                           value="{{ $nilai->nilai_huruf }}"
                           class="form-control text-center font-weight-bold" readonly>
                </div>
                <div class="form-group col-md-4">
                    <label>SKS <small class="text-muted">(otomatis)</small></label>
                    <input type="text" id="previewSksEdit"
                           value="{{ $nilai->sks }} SKS"
                           class="form-control text-center" readonly>
                </div>
            </div>
 
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Semester <span class="text-danger">*</span></label>
                    <select name="semester" class="form-control">
                        <option value="Ganjil" {{ old('semester',$nilai->semester)=='Ganjil'?'selected':'' }}>Ganjil</option>
                        <option value="Genap"  {{ old('semester',$nilai->semester)=='Genap' ?'selected':'' }}>Genap</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Tahun Akademik <span class="text-danger">*</span></label>
                    <input type="number" name="tahun_akademik"
                           value="{{ old('tahun_akademik', $nilai->tahun_akademik) }}"
                           class="form-control" min="2000" max="{{ date('Y') }}">
                </div>
            </div>
 
            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('mahasiswa.show', $nilai->mahasiswa) }}"
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Mahasiswa
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save mr-1"></i> Perbarui Nilai
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
 
@push('scripts')
<script>
function nilaiKeHuruf(angka) {
    if (isNaN(angka)) return '';
    if (angka >= 85) return 'A';
    if (angka >= 80) return 'AB';
    if (angka >= 70) return 'B';
    if (angka >= 65) return 'BC';
    if (angka >= 55) return 'C';
    if (angka >= 40) return 'D';
    return 'E';
}
document.getElementById('selectMkEdit').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    document.getElementById('hiddenNamaMkEdit').value = opt.getAttribute('data-nama') || '';
    document.getElementById('hiddenSksEdit').value    = opt.getAttribute('data-sks')  || '';
    document.getElementById('previewSksEdit').value   = (opt.getAttribute('data-sks') || '') + ' SKS';
});
document.getElementById('inputNilaiEdit').addEventListener('input', function () {
    document.getElementById('previewGradeEdit').value = nilaiKeHuruf(parseFloat(this.value));
});
</script>
@endpush
