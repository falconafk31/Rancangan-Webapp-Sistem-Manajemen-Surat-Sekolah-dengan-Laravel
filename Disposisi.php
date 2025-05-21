<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang digunakan model ini
     */
    protected $table = 'disposisi';
    
    /**
     * Atribut yang dapat diisi (mass assignable)
     */
    protected $fillable = [
        'surat_masuk_id',
        'dari_user_id',
        'kepada_user_id',
        'tanggal_disposisi',
        'isi_disposisi',
        'sifat',
        'batas_waktu',
        'status',
        'catatan',
    ];
    
    /**
     * Atribut yang harus dikonversi
     */
    protected $casts = [
        'tanggal_disposisi' => 'date',
        'batas_waktu' => 'date',
    ];
    
    /**
     * Relasi dengan SuratMasuk
     */
    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }
    
    /**
     * Relasi dengan User (dari_user_id)
     */
    public function dari()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }
    
    /**
     * Relasi dengan User (kepada_user_id)
     */
    public function kepada()
    {
        return $this->belongsTo(User::class, 'kepada_user_id');
    }
}
