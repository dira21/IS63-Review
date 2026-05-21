<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Nilai extends Model
{
    use HasFactory;
 
    protected $table = 'nilais';
 
    protected $fillable = [
        'mahasiswa_id',
        'kode_mk',
        'nama_mk',
        'sks',
        'nilai_angka',
        'nilai_huruf',
        'semester',
        'tahun_akademik',
    ];
 
    protected $casts = [
        'nilai_angka' => 'float',
    ];
 
    // ===== RELASI =====
 
    /**
     * Nilai MILIK satu Mahasiswa
     * Relasi: belongsTo
     * Akses: $nilai->mahasiswa->nama
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
 
    // ===== HELPER: konversi nilai angka ke huruf =====
    public static function konversiHuruf(float $angka): string
    {
        return match(true) {
            $angka >= 85 => 'A',
            $angka >= 80 => 'AB',
            $angka >= 70 => 'B',
            $angka >= 65 => 'BC',
            $angka >= 55 => 'C',
            $angka >= 40 => 'D',
            default      => 'E',
        };
    }
}