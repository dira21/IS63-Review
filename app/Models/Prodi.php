<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Prodi extends Model
{
    use HasFactory;
 
    protected $table = 'prodis';
 
    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
        'status',
    ];
 
    // ===== RELASI =====
 
    /**
     * Satu Prodi memiliki BANYAK Mahasiswa
     * Relasi: hasMany
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'prodi_id');
    }
}