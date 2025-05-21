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
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah', 255);
            $table->text('alamat');
            $table->string('telepon', 20);
            $table->string('email', 255);
            $table->string('website', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('kop_surat', 255)->nullable();
            $table->string('format_nomor_surat', 255);
            $table->string('pimpinan_nama', 255);
            $table->string('pimpinan_nip', 50)->nullable();
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
