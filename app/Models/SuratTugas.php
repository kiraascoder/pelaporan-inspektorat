<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'surat_tugas';

    // Primary key kustom
    protected $primaryKey = 'pengajuan_surat_id';

    // Tipe primary key
    protected $keyType = 'int';

    // Auto-increment aktif
    public $incrementing = true;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'laporan_id',
        'nomor_surat',
        'nama_ditugaskan',
        'deskripsi_umum',
        'surat_tugas_path',
        'surat_tugas_uploaded_at',
    ];


    protected $casts = [
        'nama_ditugaskan' => 'array',
        'surat_tugas_uploaded_at' => 'datetime',
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




    public function laporan()
    {
        return $this->belongsTo(LaporanPengaduan::class, 'laporan_id', 'laporan_id');
    }
}
