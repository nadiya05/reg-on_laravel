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
        Schema::create('pengajuan_ktp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // relasi ke tabeluser
            $table->enum('jenis_ktp', ['Pemula', 'Kehilangan', 'Rusak atau Ubah Status']);
            $table->string('nik')->unique();
            $table->string('nama');
            $table->date('tanggal_pengajuan');
            $table->string('kk')->nullable(); // path file KK
            $table->string('ijazah_skl')->nullable(); // path file ijazah / SKL
            $table->string('surat_kehilangan')->nullable(); // path file surat kehilangan
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_ktp');
    }
};
