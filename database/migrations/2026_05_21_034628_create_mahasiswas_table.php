<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
             $table->foreignId('prodi_id')
                  ->constrained('prodis')      // referensi ke tabel prodis
                  ->onDelete('restrict');       // cegah hapus prodi jika masih ada mahasiswa
 
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->year('angkatan');
            $table->enum('status', ['aktif', 'cuti', 'lulus', 'dropout'])
                  ->default('aktif');
            $table->string('no_hp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
