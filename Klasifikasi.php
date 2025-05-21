<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasifikasi extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang digunakan model ini
     */
    protected $table = 'klasifikasi';
    
    /**
     * Atribut yang dapat diisi (mass assignable)
     */
    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
    ];
    
    /**
     * Relasi dengan SuratMasuk
     */
    public function suratMasuk()
    {
        return $this->hasMany(SuratMasuk::class);
    }
    
    /**
     * Relasi dengan SuratKeluar
     */
    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }
    
    /**
     * Relasi dengan TemplateSurat
     */
    public function templateSurat()
    {
        return $this->hasMany(TemplateSurat::class);
    }
}
