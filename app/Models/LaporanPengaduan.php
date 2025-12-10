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
        'no_pengaduan',
        'tanggal_pengaduan',
        'pelapor_nama',
        'pelapor_pekerjaan',
        'pelapor_alamat',
        'pelapor_telp',
        'terlapor_nama',
        'terlapor_pekerjaan',
        'terlapor_alamat',
        'terlapor_telp',
        'permasalahan',
        'bukti_pendukung',
        'harapan',
        'status',
        'keterangan_admin',
        'surat_tugas_file',
        'surat_tugas_id',   // ⬅ ganti dari 'surat_id' → 'surat_tugas_id'
    ];

    protected $casts = [
        'tanggal_pengaduan' => 'date',
        'bukti_pendukung'   => 'array',
    ];

    // Scopes status
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
        // 1 laporan -> 1 pengajuan_surat_tugas (FK surat_tugas_id)
        return $this->belongsTo(PengajuanSuratTugas::class, 'surat_tugas_id', 'pengajuan_surat_id');
    }

    public function pengajuanSuratTugas()
    {
        // alternatif relasi via laporan_id
        return $this->hasOne(PengajuanSuratTugas::class, 'laporan_id', 'laporan_id');
    }

    public function getRouteKeyName()
    {
        return 'laporan_id';
    }
}
