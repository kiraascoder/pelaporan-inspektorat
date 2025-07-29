<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPengaduan extends Model
{
    use HasFactory;

    protected $table = 'laporan_pengaduan';
    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'user_id',
        'judul_laporan',
        'isi_laporan',
        'kategori',
        'prioritas',
        'status',
        'lokasi_kejadian',
        'tanggal_kejadian',
        'bukti_dokumen',
        'keterangan_admin',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'datetime',
        'bukti_dokumen' => 'array',
    ];

    // Scope untuk filter berdasarkan status
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }


    public function scopeDiterima($query)
    {
        return $query->where('status', 'Diterima');
    }

    public function scopeDalamInvestigasi($query)
    {
        return $query->where('status', 'Dalam_Investigasi');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'Ditolak');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }

    // Scope untuk prioritas
    public function scopeUrgent($query)
    {
        return $query->where('prioritas', 'Urgent');
    }

    public function scopeTinggi($query)
    {
        return $query->where('prioritas', 'Tinggi');
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function timInvestigasi()
    {
        return $this->hasOne(TimInvestigasi::class, 'laporan_id', 'laporan_id');
    }

    public function suratTugas()
    {
        return $this->hasMany(SuratTugas::class, 'laporan_id', 'laporan_id');
    }
}
