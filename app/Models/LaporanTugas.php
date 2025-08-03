<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanTugas extends Model
{
    use HasFactory;

    protected $table = 'laporan_tugas';
    protected $primaryKey = 'laporan_tugas_id';

    protected $fillable = [        
        'pegawai_id',
        'judul_laporan',
        'isi_laporan',
        'temuan',
        'rekomendasi',
        'bukti_pendukung',
        'status_laporan',
        'tanggal_submit',
    ];

    protected $casts = [
        'tanggal_submit' => 'datetime',
        'bukti_pendukung' => 'array',
    ];

    // Scope berdasarkan status
    public function scopeDraft($query)
    {
        return $query->where('status_laporan', 'Draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status_laporan', 'Submitted');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status_laporan', 'Reviewed');
    }

    public function scopeApproved($query)
    {
        return $query->where('status_laporan', 'Approved');
    }

    // Relationships
    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class, 'surat_id', 'surat_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(User::class, 'pegawai_id', 'user_id');
    }

    // Helper methods
    public function isDraft()
    {
        return $this->status_laporan === 'Draft';
    }

    public function isSubmitted()
    {
        return in_array($this->status_laporan, ['Submitted', 'Reviewed', 'Approved']);
    }

    public function isApproved()
    {
        return $this->status_laporan === 'Approved';
    }
}
