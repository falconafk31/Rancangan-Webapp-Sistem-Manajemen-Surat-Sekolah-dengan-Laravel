<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang digunakan model ini
     */
    protected $table = 'surat_masuk';
    
    /**
     * Atribut yang dapat diisi (mass assignable)
     */
    protected $fillable = [
        'no_agenda',
        'no_surat',
        'tanggal_surat',
        'tanggal_terima',
        'asal_surat',
        'tujuan',
        'perihal',
        'isi_ringkas',
        'klasifikasi_id',
        'file_path',
        'status',
        'created_by',
        'updated_by',
    ];
    
    /**
     * Atribut yang harus dikonversi
     */
    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];
    
    /**
     * Relasi dengan Klasifikasi
     */
    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class);
    }
    
    /**
     * Relasi dengan User (created_by)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Relasi dengan User (updated_by)
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    /**
     * Relasi dengan Disposisi
     */
    public function disposisi()
    {
        return $this->hasMany(Disposisi::class);
    }
    
    /**
     * Relasi dengan Lampiran
     */
    public function lampiran()
    {
        return $this->hasMany(Lampiran::class);
    }
}
