<?php
 
namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class StoreMahasiswaRequest extends FormRequest
{
    /**
     * Siapa yang boleh menggunakan request ini?
     * true = semua user yang sudah login boleh
     */
    public function authorize(): bool
    {
        return true;
    }
 
    /**
     * Aturan validasi untuk menambah mahasiswa baru
     */
    public function rules(): array
    {
        return [
            'prodi_id' => 'required|exists:prodis,id',
            'nim'      => 'required|string|max:20|unique:mahasiswas,nim',
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:mahasiswas,email',
            'angkatan' => 'required|digits:4|integer|min:2000|max:'.date('Y'),
            'status'   => 'required|in:aktif,cuti,lulus,dropout',
            'no_hp'    => 'nullable|string|max:15',
            'alamat'   => 'nullable|string',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
 
    /**
     * Pesan error kustom
     */
    public function messages(): array
    {
        return [
            'prodi_id.required' => 'Program studi wajib dipilih.',
            'prodi_id.exists'   => 'Program studi tidak valid.',
            'nim.required'      => 'NIM wajib diisi.',
            'nim.unique'        => 'NIM sudah terdaftar di sistem.',
            'email.unique'      => 'Email sudah digunakan oleh mahasiswa lain.',
            'angkatan.digits'   => 'Angkatan harus berupa tahun 4 digit.',
            'foto.image'        => 'File foto harus berupa gambar (jpg/png).',
            'foto.max'          => 'Ukuran foto maksimal 2MB.',
        ];
    }
 
    /**
     * Atribut kustom untuk nama field (ditampilkan di pesan error)
     */
    public function attributes(): array
    {
        return [
            'prodi_id' => 'Program Studi',
            'nim'      => 'NIM',
            'nama'     => 'Nama Lengkap',
            'email'    => 'Alamat Email',
            'angkatan' => 'Tahun Angkatan',
            'no_hp'    => 'Nomor HP',
        ];
    }
}
