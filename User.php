<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jabatan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
    ];
    
    /**
     * Relasi dengan SuratMasuk (created_by)
     */
    public function suratMasukCreated()
    {
        return $this->hasMany(SuratMasuk::class, 'created_by');
    }
    
    /**
     * Relasi dengan SuratMasuk (updated_by)
     */
    public function suratMasukUpdated()
    {
        return $this->hasMany(SuratMasuk::class, 'updated_by');
    }
    
    /**
     * Relasi dengan SuratKeluar (created_by)
     */
    public function suratKeluarCreated()
    {
        return $this->hasMany(SuratKeluar::class, 'created_by');
    }
    
    /**
     * Relasi dengan SuratKeluar (updated_by)
     */
    public function suratKeluarUpdated()
    {
        return $this->hasMany(SuratKeluar::class, 'updated_by');
    }
    
    /**
     * Relasi dengan Disposisi (dari_user_id)
     */
    public function disposisiDari()
    {
        return $this->hasMany(Disposisi::class, 'dari_user_id');
    }
    
    /**
     * Relasi dengan Disposisi (kepada_user_id)
     */
    public function disposisiKepada()
    {
        return $this->hasMany(Disposisi::class, 'kepada_user_id');
    }
    
    /**
     * Relasi dengan Lampiran
     */
    public function lampiran()
    {
        return $this->hasMany(Lampiran::class, 'created_by');
    }
    
    /**
     * Relasi dengan LogAktivitas
     */
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }
}
