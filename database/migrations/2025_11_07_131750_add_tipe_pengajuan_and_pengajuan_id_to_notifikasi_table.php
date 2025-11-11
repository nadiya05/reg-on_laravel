<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            // Tambah kolom enum kalau belum ada
            if (!Schema::hasColumn('notifikasi', 'tipe_pengajuan')) {
                $table->enum('tipe_pengajuan', ['KTP', 'KK', 'KIA'])->nullable()->after('status');
            }

            // Tambah kolom pengajuan_id kalau belum ada
            if (!Schema::hasColumn('notifikasi', 'pengajuan_id')) {
                $table->unsignedBigInteger('pengajuan_id')->nullable()->after('tipe_pengajuan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            if (Schema::hasColumn('notifikasi', 'tipe_pengajuan')) {
                $table->dropColumn('tipe_pengajuan');
            }

            if (Schema::hasColumn('notifikasi', 'pengajuan_id')) {
                $table->dropColumn('pengajuan_id');
            }
        });
    }
};
