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

        // metadata surat (tampilan & penandatangan)
        'tanggal_surat',
        'kota_terbit',
        'jabatan_ttd',
        'nama_ttd',
        'pangkat_ttd',
        'nip_ttd',
        'lokasi',

        // bagian list
        'dasar',
        'anggota',
        'untuk',
        'tembusan',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_surat'   => 'date',
        'dasar'           => 'array',
        'anggota'         => 'array', // ekspektasi: ['nama'=>[], 'jabatan'=>[]]
        'untuk'           => 'array',
        'tembusan'        => 'array',
    ];

    protected $appends = [
        'days_remaining',
        'is_overdue',
    ];

    /* ===================== SCOPES ===================== */

    public function scopeDraft($q)
    {
        return $q->where('status_surat', 'Draft');
    }
    public function scopeDiterbitkan($q)
    {
        return $q->where('status_surat', 'Diterbitkan');
    }
    public function scopeDalamPelaksanaan($q)
    {
        return $q->where('status_surat', 'Dalam_Pelaksanaan');
    }
    public function scopeSelesai($q)
    {
        return $q->where('status_surat', 'Selesai');
    }

    // Surat lewat tenggat & belum selesai
    public function scopeOverdue($q)
    {
        return $q->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<', now())
            ->where('status_surat', '!=', 'Selesai');
    }

    /* ================== RELATIONSHIPS ================= */

    // karena kolom relasi ada di surat_tugas (tim_id), maka belongsTo
    public function timInvestigasi()
    {
        return $this->belongsTo(TimInvestigasi::class, 'tim_id', 'tim_id');
    }

    public function laporan() // alias yang enak dipakai di view
    {
        return $this->belongsTo(LaporanPengaduan::class, 'laporan_id', 'laporan_id');
    }

    public function laporanPengaduan() // nama lama tetap ada kalau dipakai di tempat lain
    {
        return $this->laporan();
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'user_id');
    }

    /**
     * NOTE:
     * Tabel `laporan_tugas` di dump tidak punya kolom `surat_id`,
     * jadi relasi berikut DIHAPUS untuk menghindari error.
     * Kalau nanti ditambah kolom `surat_id` di `laporan_tugas`,
     * tinggal aktifkan lagi:
     *
     * public function laporanTugas()
     * {
     *     return $this->hasMany(LaporanTugas::class, 'surat_id', 'surat_id');
     * }
     */

    /* ===================== HELPERS ==================== */

    public function getIsOverdueAttribute(): bool
    {
        if ($this->status_surat === 'Selesai' || !$this->tanggal_selesai) {
            return false;
        }
        return $this->tanggal_selesai->lt(now());
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->tanggal_selesai) {
            return null; // tidak ditentukan tenggat
        }
        if ($this->status_surat === 'Selesai') {
            return 0;
        }
        $days = now()->diffInDays($this->tanggal_selesai, false);
        return max($days, 0);
    }
}
