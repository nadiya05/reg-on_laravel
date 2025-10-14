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
        Schema::create('pengajuan_kia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // relasi ke tabel users
            $table->string('nomor_antrean')->nullable();
            $table->enum('jenis_kia', ['pemula']); // enumerasi jenis KIA
            $table->string('nik', 16);
            $table->string('nama');
            $table->date('tanggal_pengajuan');
            $table->enum('status', ['Sedang Diproses', 'Selesai', 'Ditolak'])->default('Sedang Diproses');

            // Dokumen dalam bentuk gambar (path file)
            $table->string('kk')->nullable();
            $table->string('akta_lahir')->nullable();
            $table->string('surat_nikah')->nullable();
            $table->string('ktp_ortu')->nullable();
            $table->string('pass_foto')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kia');
    }
};
