<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@simahaswa.com',
            'password' => Hash::make('password123'), // WAJIB di-hash!
        ]);
 
        $this->command->info('UserSeeder: Akun admin berhasil dibuat.');
        $this->command->info('Login: admin@simahaswa.com | password123');
    }
}
