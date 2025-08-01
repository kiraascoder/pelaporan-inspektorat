<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    


    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'email',
        'password',
        'nama_lengkap',
        'no_telepon',
        'alamat',
        'role',
        'nip',
        'jabatan',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];


    public function scopeWarga($query)
    {
        return $query->where('role', 'Warga');
    }

    public function scopePegawai($query)
    {
        return $query->where('role', 'Pegawai');
    }

    public function scopeKetuaBidang($query)
    {
        return $query->where('role', 'Ketua_Bidang_Investigasi');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'Admin');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Relationships


    public function laporanPengaduan()
    {
        return $this->hasMany(LaporanPengaduan::class, 'user_id', 'user_id');
    }


    public function timInvestigasiDipimpin()
    {
        return $this->hasMany(TimInvestigasi::class, 'ketua_tim_id', 'user_id');
    }


    public function timInvestigasiDiikuti()
    {
        return $this->belongsToMany(TimInvestigasi::class, 'anggota_tim', 'pegawai_id', 'tim_id')
            ->withPivot('role_dalam_tim', 'tanggal_bergabung', 'is_active')
            ->withTimestamps();
    }


    public function suratTugasDibuat()
    {
        return $this->hasMany(SuratTugas::class, 'dibuat_oleh', 'user_id');
    }


    public function laporanTugas()
    {
        return $this->hasMany(LaporanTugas::class, 'pegawai_id', 'user_id');
    }

    // Helper methods
    public function isWarga()
    {
        return $this->role === 'Warga';
    }

    public function isPegawai()
    {
        return $this->role === 'Pegawai';
    }

    public function isKetuaBidang()
    {
        return $this->role === 'Ketua_Bidang_Investigasi';
    }

    public function isAdmin()
    {
        return $this->role === 'Admin';
    }
}
