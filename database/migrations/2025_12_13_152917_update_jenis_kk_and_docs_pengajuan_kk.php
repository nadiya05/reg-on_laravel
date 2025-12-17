<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * ==========================
         * UPDATE ENUM jenis_kk
         * ==========================
         */
        DB::statement("
            ALTER TABLE pengajuan_kk
            MODIFY jenis_kk ENUM(
                'pendidikan',
                'pekerjaan',
                'status_perkawinan',
                'gol_darah',
                'penambahan_anggota',
                'penggabungan_kk',
                'pindahan',
                'pisah_kk'
            ) NOT NULL
        ");

        /**
         * ==========================
         * TAMBAH KOLOM DOKUMEN BARU
         * ==========================
         */
        Schema::table('pengajuan_kk', function (Blueprint $table) {
            $table->string('akta_cerai')->nullable()->after('surat_kematian');
            $table->string('bukti_cek_darah')->nullable()->after('ijazah');
            $table->string('surat_penggabungan_kk')->nullable()->after('akta_kelahiran');
            $table->string('surat_pisah_kk')->nullable()->after('surat_penggabungan_kk');
        });
    }

    public function down(): void
    {
        /**
         * KEMBALIKAN ENUM KE KONDISI AWAL
         */
        DB::statement("
            ALTER TABLE pengajuan_kk
            MODIFY jenis_kk ENUM('pemula', 'ubah status') NOT NULL
        ");

        /**
         * HAPUS KOLOM YANG DITAMBAHKAN
         */
        Schema::table('pengajuan_kk', function (Blueprint $table) {
            $table->dropColumn([
                'akta_cerai',
                'bukti_cek_darah',
                'surat_penggabungan_kk',
                'surat_pisah_kk',
            ]);
        });
    }
};
