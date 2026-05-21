<?php
// database/seeders/NilaiSeeder.php
 
namespace Database\Seeders;
 
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Database\Seeder;
 
class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        if (Mahasiswa::count() === 0) {
            $this->command->warn('MahasiswaSeeder harus dijalankan lebih dulu!');
            return;
        }
 
        // Setiap mahasiswa mendapat 4-6 nilai matakuliah
        Mahasiswa::all()->each(function ($mahasiswa) {
            $jumlah = rand(4, 6);
            Nilai::factory($jumlah)->create([
                'mahasiswa_id' => $mahasiswa->id,
            ]);
        });
 
        $this->command->info('NilaiSeeder: ' . Nilai::count() . ' data nilai berhasil dibuat.');
    }
}