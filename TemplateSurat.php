<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang digunakan model ini
     */
    protected $table = 'template_surat';
    
    /**
     * Atribut yang dapat diisi (mass assignable)
     */
    protected $fillable = [
        'nama',
        'jenis',
        'klasifikasi_id',
        'konten',
        'created_by',
        'updated_by',
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
}
