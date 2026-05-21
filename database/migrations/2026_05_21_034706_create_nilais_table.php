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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
             $table->foreignId('mahasiswa_id')
                  ->constrained('mahasiswas')
                  ->onDelete('cascade'); // jika mahasiswa dihapus, nilai ikut terhapus
 
            $table->string('kode_mk', 10);
            $table->string('nama_mk', 100);
            $table->tinyInteger('sks');
            $table->decimal('nilai_angka', 5, 2);
            $table->string('nilai_huruf', 2);
            $table->string('semester', 10);
            $table->year('tahun_akademik');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
