<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuk');
            $table->foreignId('dari_user_id')->constrained('users');
            $table->foreignId('kepada_user_id')->constrained('users');
            $table->date('tanggal_disposisi');
            $table->text('isi_disposisi');
            $table->enum('sifat', ['biasa', 'segera', 'sangat_segera', 'rahasia'])->default('biasa');
            $table->date('batas_waktu')->nullable();
            $table->enum('status', ['belum_dibaca', 'dibaca', 'diproses', 'selesai'])->default('belum_dibaca');
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Indeks
            $table->index('surat_masuk_id');
            $table->index('dari_user_id');
            $table->index('kepada_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};
