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
        Schema::create('pengajuan_kk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // relasi ke tabel users
            $table->string('nomor_antrean')->nullable();
            $table->enum('jenis_kk', ['pemula', 'ubah status']); // jenis KK
            $table->string('nik', 16);
            $table->string('nama');
            $table->date('tanggal_pengajuan');
            $table->enum('status', ['Sedang Diproses', 'Selesai', 'Ditolak'])->default('Sedang Diproses');

            // Dokumen dalam bentuk path file
            $table->string('formulir_permohonan_kk')->nullable();
            $table->string('surat_nikah')->nullable();
            $table->string('surat_keterangan_pindah')->nullable();
            $table->string('kk_asli')->nullable();
            $table->string('surat_kematian')->nullable();
            $table->string('akta_kelahiran')->nullable();
            $table->string('ijazah')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kk');
    }
};
