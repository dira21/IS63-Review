<?php
// database/seeders/ProdiSeeder.php
 
namespace Database\Seeders;
 
use App\Models\Prodi;
use Illuminate\Database\Seeder;
 
class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        // Data prodi yang pasti ada (data statis)
        $prodis = [
            ['kode_prodi' => 'TI',  'nama_prodi' => 'Teknik Informatika',       'jenjang' => 'S1', 'status' => 'aktif'],
            ['kode_prodi' => 'SI',  'nama_prodi' => 'Sistem Informasi',          'jenjang' => 'S1', 'status' => 'aktif'],
            ['kode_prodi' => 'TK',  'nama_prodi' => 'Teknik Komputer',           'jenjang' => 'S1', 'status' => 'aktif'],
            ['kode_prodi' => 'MI',  'nama_prodi' => 'Manajemen Informatika',      'jenjang' => 'D3', 'status' => 'aktif'],
            ['kode_prodi' => 'RPL', 'nama_prodi' => 'Rekayasa Perangkat Lunak',   'jenjang' => 'S1', 'status' => 'aktif'],
        ];
 
        foreach ($prodis as $prodi) {
            Prodi::create($prodi);
        }
 
        $this->command->info('ProdiSeeder: ' . count($prodis) . ' prodi berhasil dibuat.');
    }
}