<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaTim extends Model
{
    use HasFactory;

    protected $table = 'anggota_tim';
    protected $primaryKey = 'anggota_id';

    protected $fillable = [
        'tim_id',
        'pegawai_id',
        'role_dalam_tim',
        'tanggal_bergabung',
        'is_active',
    ];

    protected $casts = [
        'tanggal_bergabung' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Scope untuk anggota aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope berdasarkan role dalam tim
    public function scopeKetua($query)
    {
        return $query->where('role_dalam_tim', 'Ketua');
    }

    public function scopeAnggota($query)
    {
        return $query->where('role_dalam_tim', 'Anggota');
    }

    public function scopeKoordinator($query)
    {
        return $query->where('role_dalam_tim', 'Koordinator');
    }

    // Relationships
    public function timInvestigasi()
    {
        return $this->belongsTo(TimInvestigasi::class, 'tim_id', 'tim_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(User::class, 'pegawai_id', 'user_id');
    }
}
