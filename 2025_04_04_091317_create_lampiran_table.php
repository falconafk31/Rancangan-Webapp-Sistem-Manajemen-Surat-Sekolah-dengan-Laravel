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
        Schema::create('lampiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->nullable()->constrained('surat_masuk');
            $table->foreignId('surat_keluar_id')->nullable()->constrained('surat_keluar');
            $table->string('nama_file', 255);
            $table->string('file_path', 255);
            $table->integer('ukuran_file');
            $table->string('tipe_file', 50);
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            // Indeks
            $table->index('surat_masuk_id');
            $table->index('surat_keluar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran');
    }
};
