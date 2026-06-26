<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penandatangan extends Model
{
    protected $table = 'penandatangan';
    protected $primaryKey = 'penandatangan_id';

    protected $fillable = [
        'nama',
        'jabatan',
        'pangkat',
        'nip',
        'ttd_image',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function getTtdImageUrlAttribute()
    {
        return $this->ttd_image ? asset('storage/' . $this->ttd_image) : null;
    }
}
