<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang digunakan model ini
     */
    protected $table = 'lampiran';
    
    /**
     * Atribut yang dapat diisi (mass assignable)
     */
    protected $fillable = [
        'surat_masuk_id',
        'surat_keluar_id',
        'nama_file',
        'file_path',
        'ukuran_file',
        'tipe_file',
        'keterangan',
        'created_by',
    ];
    
    /**
     * Relasi dengan SuratMasuk
     */
    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class);
    }
    
    /**
     * Relasi dengan SuratKeluar
     */
    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class);
    }
    
    /**
     * Relasi dengan User (created_by)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
