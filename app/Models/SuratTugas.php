<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    use HasFactory;

    protected $table = 'surat_tugas';
    protected $primaryKey = 'surat_id';

    protected $fillable = [
        'nomor_surat',
        'tim_id',
        'laporan_id',
        'dibuat_oleh',
        'perihal',
        'deskripsi_tugas',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_surat',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    // Scope berdasarkan status
    public function scopeDraft($query)
    {
        return $query->where('status_surat', 'Draft');
    }

    public function scopeDiterbitkan($query)
    {
        return $query->where('status_surat', 'Diterbitkan');
    }

    public function scopeDalamPelaksanaan($query)
    {
        return $query->where('status_surat', 'Dalam_Pelaksanaan');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status_surat', 'Selesai');
    }

    // Scope untuk surat yang sudah deadline
    public function scopeOverdue($query)
    {
        return $query->where('tanggal_selesai', '<', now())
            ->whereNotIn('status_surat', ['Selesai']);
    }

    // Relationships
    public function timInvestigasi()
    {
        return $this->hasOne(TimInvestigasi::class, 'surat_id', 'surat_id');
    }

    public function laporanPengaduan()
    {
        return $this->belongsTo(LaporanPengaduan::class, 'laporan_id', 'laporan_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'user_id');
    }

    public function laporanTugas()
    {
        return $this->hasMany(LaporanTugas::class, 'surat_id', 'surat_id');
    }

    // Helper methods
    public function isOverdue()
    {
        return $this->tanggal_selesai < now() && $this->status_surat !== 'Selesai';
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->status_surat === 'Selesai') {
            return 0;
        }

        $remaining = now()->diffInDays($this->tanggal_selesai, false);
        return $remaining < 0 ? 0 : $remaining;
    }
}
