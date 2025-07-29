<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimInvestigasi extends Model
{
    protected $table = 'tim_investigasi';

    protected $primaryKey = 'tim_id';

    protected $fillable = [
        'laporan_id',
        'ketua_tim_id',
        'nama_tim',
        'deskripsi_tim',
        'status_tim',
    ];

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_tim', 'Aktif');
    }

    public function scopeNonAktif($query)
    {
        return $query->where('status_tim', '!=', 'Aktif');
    }

    // Relationships
    public function anggota()
    {
        return $this->belongsToMany(User::class, 'anggota_tim', 'tim_id', 'pegawai_id')
            ->withPivot('role_dalam_tim', 'tanggal_bergabung', 'is_active')
            ->withTimestamps();
    }

    // Get only active members
    public function anggotaAktif()
    {
        return $this->belongsToMany(User::class, 'anggota_tim', 'tim_id', 'pegawai_id')
            ->withPivot('role_dalam_tim', 'tanggal_bergabung', 'is_active')
            ->wherePivot('is_active', true)
            ->withTimestamps();
    }

    // Relationship to team leader
    public function ketuaTim()
    {
        return $this->belongsTo(User::class, 'ketua_tim_id', 'user_id');
    }

    // Relationship to laporan pengaduan (if applicable)
    public function laporanPengaduan()
    {
        return $this->belongsTo(LaporanPengaduan::class, 'laporan_id', 'laporan_id');
    }

    // Relationship to surat tugas
    public function suratTugas()
    {
        return $this->hasMany(SuratTugas::class, 'tim_id', 'tim_id');
    }

    // Helper methods
    public function isAktif()
    {
        return $this->status_tim === 'Aktif';
    }

    public function getJumlahAnggotaAttribute()
    {
        return $this->anggota()->count();
    }

    public function getJumlahAnggotaAktifAttribute()
    {
        return $this->anggotaAktif()->count();
    }
}
