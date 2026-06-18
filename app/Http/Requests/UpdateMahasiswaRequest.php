<?php
 
namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class UpdateMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
 
    public function rules(): array
    {
        // Ambil ID mahasiswa dari URL parameter {mahasiswa}
        $mahasiswaId = $this->route('mahasiswa')->id;
 
        return [
            'prodi_id' => 'required|exists:prodis,id',
            // unique kecuali ID mahasiswa yang sedang diedit
            'nim'      => 'required|string|max:20|unique:mahasiswas,nim,'.$mahasiswaId,
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:mahasiswas,email,'.$mahasiswaId,
            'angkatan' => 'required|digits:4|integer|min:2000|max:'.date('Y'),
            'status'   => 'required|in:aktif,cuti,lulus,dropout',
            'no_hp'    => 'nullable|string|max:15',
            'alamat'   => 'nullable|string',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
 
    public function messages(): array
    {
        return [
            'nim.unique'   => 'NIM sudah digunakan oleh mahasiswa lain.',
            'email.unique' => 'Email sudah digunakan oleh mahasiswa lain.',
        ];
    }
}