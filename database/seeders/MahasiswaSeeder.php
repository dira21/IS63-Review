<?php
// database/seeders/MahasiswaSeeder.php
 
namespace Database\Seeders;
 
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Database\Seeder;
 
class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan data prodi sudah ada
        if (Prodi::count() === 0) {
            $this->command->warn('ProdiSeeder harus dijalankan lebih dulu!');
            return;
        }
 
        // Buat 30 mahasiswa dummy menggunakan Factory
        Mahasiswa::factory(30)->create();
 
        // Tambah 1 data mahasiswa manual untuk keperluan demo
        $prodi = Prodi::where('kode_prodi', 'TI')->first();
        Mahasiswa::create([
            'prodi_id'  => $prodi->id,
            'nim'       => '2021001001',
            'nama'      => 'Ahmad Rizky Pratama',
            'email'     => 'ahmad.rizky@example.com',
            'angkatan'  => 2021,
            'status'    => 'aktif',
            'no_hp'     => '081234567890',
            'alamat'    => 'Jl. Sudirman No. 10, Jakarta',
        ]);
 
        $this->command->info('MahasiswaSeeder: ' . Mahasiswa::count() . ' mahasiswa berhasil dibuat.');
    }
}