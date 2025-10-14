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
        Schema::table('pengajuan_ktp', function (Blueprint $table) {
            $table->enum('status', ['sedang diproses', 'selesai', 'ditolak'])
                  ->default('sedang diproses')
                  ->after('id'); // ubah 'id' jadi kolom terakhir sebelum kolom ini, sesuai kebutuhan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_ktp', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
