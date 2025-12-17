<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pengajuan_kk
            MODIFY jenis_kk ENUM(
                'pendidikan',
                'status_perkawinan',
                'gol_darah',
                'penambahan_anggota',
                'pindahan',
                'pisah_kk',
                'perceraian',
                'kematian'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE pengajuan_kk
            MODIFY jenis_kk ENUM(
                'pendidikan',
                'status_perkawinan',
                'gol_darah',
                'penambahan_anggota',
                'pindahan',
                'pisah_kk'
            ) NOT NULL
        ");
    }
};
