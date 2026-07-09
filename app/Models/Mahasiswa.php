<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
    protected $casts = [
        'angkatan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELASI =====
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'mahasiswa_id');
    }

    // ===== SCOPE =====
    public function scopeAktif($q)
    {
        return $q->where('status', 'aktif');
    }
    public function scopeAngkatan($q, int $t)
    {
        return $q->where('angkatan', $t);
    }
    public function scopeDariProdi($q, int $id)
    {
        return $q->where('prodi_id', $id);
    }
    public function scopeCari($q, string $kw)
    {
        return $q->where(fn($s) => $s->where('nama', 'like', "%{$kw}%")->orWhere('nim', 'like', "%{$kw}%"));
    }

    // ===== ACCESSOR & MUTATOR =====
    protected function nama(): Attribute
    {
        return Attribute::make(get: fn($v) => ucwords(strtolower($v)));
    }
    protected function nim(): Attribute
    {
        return Attribute::make(set: fn($v) => strtoupper(trim($v)));
    }
    protected function statusLabel(): Attribute
    {
        return Attribute::make(get: fn() => match ($this->status) {
            'aktif' => 'success',
            'cuti' => 'warning',
            'lulus' => 'info',
            'dropout' => 'danger',
            default => 'secondary',
        });
    }

    // ===== HELPER =====
    public function getRataRataNilaiAttribute(): float
    {
        return round($this->nilais->avg('nilai_angka') ?? 0, 2);
    }
}