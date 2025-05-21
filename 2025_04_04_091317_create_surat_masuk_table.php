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
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no_agenda', 100);
            $table->string('no_surat', 100);
            $table->date('tanggal_surat');
            $table->date('tanggal_terima');
            $table->string('asal_surat', 255);
            $table->string('tujuan', 255);
            $table->string('perihal', 255);
            $table->text('isi_ringkas')->nullable();
            $table->foreignId('klasifikasi_id')->constrained('klasifikasi');
            $table->string('file_path', 255)->nullable();
            $table->enum('status', ['belum_diproses', 'sedang_diproses', 'selesai'])->default('belum_diproses');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
            
            // Indeks
            $table->index('no_surat');
            $table->index('tanggal_surat');
            $table->index('tanggal_terima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
