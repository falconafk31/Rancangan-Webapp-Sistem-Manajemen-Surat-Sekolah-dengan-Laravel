<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang digunakan model ini
     */
    protected $table = 'log_aktivitas';
    
    /**
     * Atribut yang dapat diisi (mass assignable)
     */
    protected $fillable = [
        'user_id',
        'aktivitas',
        'modul',
        'ip_address',
        'user_agent',
    ];
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];
    
    /**
     * Relasi dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
