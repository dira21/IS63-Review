<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
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
        'sks'            => 'integer',
        'tahun_akademik' => 'integer',
    ];
 
    // ===== RELASI =====
 
    /**
     * Nilai MILIK satu Mahasiswa
     * Relasi: belongsTo
     * Akses: $nilai->mahasiswa->nama
     */
    public function mahasiswa(): BelongsTo
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
 
    // Scope: filter semester tertentu
    // Penggunaan: Nilai::semester('Ganjil')->get()
    public function scopeSemester($query, string $semester)
    {
        return $query->where('semester', $semester);
    }
 
    // Scope: filter tahun akademik
    // Penggunaan: Nilai::tahunAkademik(2023)->get()
    public function scopeTahunAkademik($query, int $tahun)
    {
        return $query->where('tahun_akademik', $tahun);
    }
 
    // Scope: nilai lulus (nilai_angka >= 55)
    // Penggunaan: Nilai::lulus()->get()
    public function scopeLulus($query)
    {
        return $query->where('nilai_angka', '>=', 55);
    }

    // Accessor: tampilkan nilai dengan format lengkap "85.50 (A)"
    // Akses: $nilai->nilai_lengkap
    protected function nilaiLengkap(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->nilai_angka, 2) . ' (' . $this->nilai_huruf . ')',
        );
    }
 
    // Accessor: warna badge berdasarkan nilai huruf
    // Akses: $nilai->warna_badge
    protected function warnaBadge(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->nilai_huruf) {
                'A' => 'success',
                'AB' => 'primary',
                'B' => 'info',
                'BC' => 'secondary',
                'C' => 'warning',
                default => 'danger',
            }
        );
    }
}
