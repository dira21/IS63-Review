<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Mahasiswa extends Model
{
    use HasFactory;
 
    protected $table = 'mahasiswas';
 
    protected $fillable = [
        'prodi_id',
        'nim',
        'nama',
        'email',
        'angkatan',
        'status',
        'no_hp',
        'alamat',
        'foto',
    ];
 
    // ===== RELASI =====
 
    /**
     * Mahasiswa MILIK satu Prodi
     * Relasi: belongsTo
     * Akses: $mahasiswa->prodi->nama_prodi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
 
    /**
     * Satu Mahasiswa memiliki BANYAK Nilai
     * Relasi: hasMany
     * Akses: $mahasiswa->nilais
     */
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'mahasiswa_id');
    }
 
    // ===== HELPER METHOD =====
 
    /**
     * Hitung IPK dari rata-rata nilai angka
     */
    public function getIpkAttribute(): float
    {
        if ($this->nilais->isEmpty()) return 0;
        return round($this->nilais->avg('nilai_angka') / 25, 2); // konversi ke skala 4
    }
}