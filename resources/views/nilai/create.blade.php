{{-- resources/views/nilai/create.blade.php --}}
@extends('layouts.app')
 
@section('title', 'Tambah Nilai')
@section('page-title', 'Tambah Data Nilai')
 
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-plus-circle mr-2"></i>Form Tambah Nilai Mahasiswa
        </h6>
    </div>
    <div class="card-body">
 
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif
 
        <form action="{{ route('nilai.store') }}" method="POST">
            @csrf
 
            {{-- Pilih Mahasiswa --}}
            <div class="form-group">
                <label>Mahasiswa <span class="text-danger">*</span></label>
                <select name="mahasiswa_id"
                        class="form-control {{ $errors->has('mahasiswa_id') ? 'is-invalid' : '' }}">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach($mahasiswas as $mhs)
                        <option value="{{ $mhs->id }}"
                            {{ old('mahasiswa_id', $selectedMahasiswa->id ?? '') == $mhs->id
                                ? 'selected' : '' }}>
                            {{ $mhs->nim }} - {{ $mhs->nama }}
                            ({{ $mhs->prodi->nama_prodi ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('mahasiswa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
 
            {{-- Pilih Matakuliah (auto-fill via JS) --}}
            <div class="form-group">
                <label>Matakuliah <span class="text-danger">*</span></label>
                <select name="kode_mk" id="selectMk"
                        class="form-control {{ $errors->has('kode_mk') ? 'is-invalid' : '' }}">
                    <option value="">-- Pilih Matakuliah --</option>
                    @foreach($matakuliahs as $mk)
                        <option value="{{ $mk['kode'] }}"
                                data-nama="{{ $mk['nama'] }}"
                                data-sks="{{ $mk['sks'] }}"
                                {{ old('kode_mk') == $mk['kode'] ? 'selected' : '' }}>
                            [{{ $mk['kode'] }}] {{ $mk['nama'] }} — {{ $mk['sks'] }} SKS
                        </option>
                    @endforeach
                </select>
                @error('kode_mk')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
 
            {{-- Hidden fields diisi otomatis oleh JavaScript --}}
            <input type="hidden" name="nama_mk" id="hiddenNamaMk" value="{{ old('nama_mk') }}">
            <input type="hidden" name="sks"     id="hiddenSks"    value="{{ old('sks') }}">
 
            <div class="form-row">
                {{-- Nilai Angka --}}
                <div class="form-group col-md-4">
                    <label>Nilai Angka <span class="text-danger">*</span></label>
                    <input type="number" name="nilai_angka" id="inputNilaiAngka"
                           value="{{ old('nilai_angka') }}"
                           class="form-control {{ $errors->has('nilai_angka') ? 'is-invalid' : '' }}"
                           min="0" max="100" step="0.01" placeholder="0 - 100">
                    @error('nilai_angka')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                {{-- Preview Grade (read-only, diisi JS) --}}
                <div class="form-group col-md-4">
                    <label>Grade <small class="text-muted">(otomatis)</small></label>
                    <input type="text" id="previewGrade"
                           class="form-control text-center font-weight-bold"
                           placeholder="Isi nilai angka..." readonly>
                </div>
                {{-- Preview SKS (read-only, diisi JS) --}}
                <div class="form-group col-md-4">
                    <label>SKS <small class="text-muted">(otomatis)</small></label>
                    <input type="text" id="previewSks"
                           class="form-control text-center"
                           placeholder="Pilih matakuliah..." readonly>
                </div>
            </div>
 
            <div class="form-row">
                {{-- Semester --}}
                <div class="form-group col-md-6">
                    <label>Semester <span class="text-danger">*</span></label>
                    <select name="semester"
                            class="form-control {{ $errors->has('semester') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Semester --</option>
                        <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected':'' }}>Ganjil</option>
                        <option value="Genap"  {{ old('semester') == 'Genap'  ? 'selected':'' }}>Genap</option>
                    </select>
                    @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                {{-- Tahun Akademik --}}
                <div class="form-group col-md-6">
                    <label>Tahun Akademik <span class="text-danger">*</span></label>
                    <input type="number" name="tahun_akademik"
                           value="{{ old('tahun_akademik', date('Y')) }}"
                           class="form-control {{ $errors->has('tahun_akademik') ? 'is-invalid' : '' }}"
                           min="2000" max="{{ date('Y') }}">
                    @error('tahun_akademik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
 
            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('nilai.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Nilai
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
// Auto-fill nama_mk dan SKS saat matakuliah dipilih
document.getElementById('selectMk').addEventListener('change', function () {
    const opt  = this.options[this.selectedIndex];
    const nama = opt.getAttribute('data-nama') || '';
    const sks  = opt.getAttribute('data-sks')  || '';
    document.getElementById('hiddenNamaMk').value = nama;
    document.getElementById('hiddenSks').value    = sks;
    document.getElementById('previewSks').value   = sks ? sks + ' SKS' : '';
});
 
// Konversi nilai angka ke huruf (sama dengan Model Nilai::konversiHuruf)
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
 
document.getElementById('inputNilaiAngka').addEventListener('input', function () {
    document.getElementById('previewGrade').value = nilaiKeHuruf(parseFloat(this.value));
});
</script>
@endpush
