<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSuratTugas extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'pengajuan_surat_tugas';

    // Primary key kustom
    protected $primaryKey = 'pengajuan_surat_id';

    // Tipe primary key
    protected $keyType = 'int';

    // Auto-increment aktif
    public $incrementing = true;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'laporan_id',
        'penandatangan_id',
        'nomor_surat',
        'status',
        'deskripsi_umum',
    ];

    // ========================
    // 🔗 RELASI ANTAR TABEL
    // ========================

    /**
     * Relasi ke tabel laporan_pengaduan
     * 1 pengajuan surat tugas -> 1 laporan pengaduan
     */
    public function laporanPengaduan()
    {
        return $this->belongsTo(LaporanPengaduan::class, 'laporan_id', 'laporan_id');
    }

    /**
     * Relasi ke tabel users (penandatangan)
     * 1 pengajuan surat tugas -> 1 user penandatangan
     */
    public function penandatangan()
    {
        return $this->belongsTo(User::class, 'penandatangan_id', 'user_id');
    }

    /**
     * Relasi ke anggota tim (jika ada tabel pengajuan_surat_tugas_anggota)
     * 1 pengajuan surat tugas -> banyak anggota
     */
    public function anggota()
    {
        return $this->hasMany(PengajuanSuratTugasAnggota::class, 'pengajuan_id', 'pengajuan_surat_id');
    }
}
