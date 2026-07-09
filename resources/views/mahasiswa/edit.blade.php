{{-- resources/views/mahasiswa/edit.blade.php --}}
@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp
 
@section('title', 'Edit Mahasiswa')
@section('page-title', 'Edit Data Mahasiswa')
 
@section('content')
<div class="row justify-content-center">
<div class="col-lg-10">
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-warning">
            <i class="fas fa-user-edit mr-2"></i>Edit: {{ $mahasiswa->nama }}
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('mahasiswa.update', $mahasiswa) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')
 
            <div class="row">
                <div class="col-md-8">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>NIM <span class="text-danger">*</span></label>
                            <input type="text" name="nim"
                                   value="{{ old('nim', $mahasiswa->nim) }}"
                                   class="form-control {{ $errors->has('nim') ? 'is-invalid' : '' }}">
                            @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Program Studi <span class="text-danger">*</span></label>
                            <select name="prodi_id"
                                    class="form-control {{ $errors->has('prodi_id') ? 'is-invalid' : '' }}">
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}"
                                        {{ old('prodi_id', $mahasiswa->prodi_id) == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
 
                    <div class="form-group">
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama"
                               value="{{ old('nama', $mahasiswa->nama) }}"
                               class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
 
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email"
                               value="{{ old('email', $mahasiswa->email) }}"
                               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
 
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Angkatan <span class="text-danger">*</span></label>
                            <input type="number" name="angkatan"
                                   value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                                   class="form-control {{ $errors->has('angkatan') ? 'is-invalid' : '' }}"
                                   min="2000" max="{{ date('Y') }}">
                            @error('angkatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status"
                                    class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                @foreach(['aktif','cuti','lulus','dropout'] as $s)
                                    <option value="{{ $s }}"
                                        {{ old('status', $mahasiswa->status) == $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label>No. HP</label>
                            <input type="text" name="no_hp"
                                   value="{{ old('no_hp', $mahasiswa->no_hp) }}"
                                   class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}">
                            @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
 
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" rows="3"
                                  class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}">{{ old('alamat', $mahasiswa->alamat) }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
 
                {{-- Kolom Kanan: Foto --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Foto Mahasiswa</label>
                        <div class="text-center mb-3">
                            <img id="preview-foto"
                                 src="{{ $mahasiswa->foto
                                     ? Storage::url($mahasiswa->foto)
                                     : asset('vendor/startbootstrap-sb-admin-2/img/undraw_profile.svg') }}"
                                 class="img-thumbnail rounded" width="150" height="150"
                                 style="object-fit:cover">
                        </div>
                        <input type="file" name="foto" id="foto"
                               class="form-control-file {{ $errors->has('foto') ? 'is-invalid' : '' }}"
                               accept="image/jpg,image/jpeg,image/png"
                               onchange="previewFoto(this)">
                        <small class="form-text text-muted">
                            Kosongkan jika tidak ingin mengganti foto.
                        </small>
                        @error('foto')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
 
            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('mahasiswa.show', $mahasiswa) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save mr-1"></i> Perbarui Data
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
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('preview-foto').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
