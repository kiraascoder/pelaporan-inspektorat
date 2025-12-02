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
        'nama_ditugaskan',
        'deskripsi_umum',
    ];


    protected $casts = [
        'nama_ditugaskan' => 'array',
    ];

    public const STATUS_PENDING = 'Pending';
    public const STATUS_DIBUAT  = 'Dibuat';
    public const STATUS_SELESAI = 'Selesai';


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


    public function laporan()
    {
        return $this->belongsTo(LaporanPengaduan::class, 'laporan_id', 'laporan_id');
    }

    /** Relasi ke User penandatangan (Kepala Inspektorat) */
    public function penandatangan()
    {
        return $this->belongsTo(User::class, 'penandatangan_id', 'user_id');
    }
}
