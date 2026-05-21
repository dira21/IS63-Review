<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
 
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan ini WAJIB diikuti karena adanya foreign key:
        // 1. Prodi dulu (tidak bergantung pada tabel lain)
        // 2. Mahasiswa (bergantung pada prodis)
        // 3. Nilai (bergantung pada mahasiswas)
        $this->call([
            ProdiSeeder::class,
            MahasiswaSeeder::class,
            NilaiSeeder::class,
        ]);
    }
}