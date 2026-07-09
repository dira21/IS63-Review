{{-- resources/views/prodi/create.blade.php --}}
@extends('layouts.app')
 
@section('title', 'Tambah Prodi')
@section('page-title', 'Tambah Program Studi')
 
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-plus-circle mr-2"></i>Form Tambah Program Studi
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('prodi.store') }}" method="POST">
            @csrf
 
            <div class="form-row">
                {{-- Kode Prodi --}}
                <div class="form-group col-md-4">
                    <label>Kode Prodi <span class="text-danger">*</span></label>
                    <input type="text" name="kode_prodi"
                           value="{{ old('kode_prodi') }}"
                           class="form-control {{ $errors->has('kode_prodi') ? 'is-invalid' : '' }}"
                           placeholder="Contoh: TI, SI, TK" maxlength="10">
                    @error('kode_prodi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
 
                {{-- Jenjang --}}
                <div class="form-group col-md-4">
                    <label>Jenjang <span class="text-danger">*</span></label>
                    <select name="jenjang"
                            class="form-control {{ $errors->has('jenjang') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Jenjang --</option>
                        @foreach(['D3','S1','S2','S3'] as $j)
                            <option value="{{ $j }}" {{ old('jenjang') == $j ? 'selected' : '' }}>
                                {{ $j }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenjang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
 
                {{-- Status --}}
                <div class="form-group col-md-4">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status"
                            class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <option value="aktif"    {{ old('status','aktif') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
 
            {{-- Nama Prodi --}}
            <div class="form-group">
                <label>Nama Program Studi <span class="text-danger">*</span></label>
                <input type="text" name="nama_prodi"
                       value="{{ old('nama_prodi') }}"
                       class="form-control {{ $errors->has('nama_prodi') ? 'is-invalid' : '' }}"
                       placeholder="Contoh: Teknik Informatika">
                @error('nama_prodi')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
 
            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('prodi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection