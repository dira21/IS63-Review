<?php
 
namespace Database\Factories;
 
use Illuminate\Database\Eloquent\Factories\Factory;
 
class ProdiFactory extends Factory
{
    public function definition(): array
    {
        $prodis = [
            ['kode' => 'TI',  'nama' => 'Teknik Informatika',      'jenjang' => 'S1'],
            ['kode' => 'SI',  'nama' => 'Sistem Informasi',         'jenjang' => 'S1'],
            ['kode' => 'TK',  'nama' => 'Teknik Komputer',          'jenjang' => 'S1'],
            ['kode' => 'MI',  'nama' => 'Manajemen Informatika',     'jenjang' => 'D3'],
            ['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak',  'jenjang' => 'S1'],
            ['kode' => 'MTI', 'nama' => 'Magister Teknik Informatika','jenjang' => 'S2'],
        ];
 
        $prodi = fake()->unique()->randomElement($prodis);
 
        return [
            'kode_prodi' => $prodi['kode'],
            'nama_prodi' => $prodi['nama'],
            'jenjang'    => $prodi['jenjang'],
            'status'     => 'aktif',
        ];
    }
}