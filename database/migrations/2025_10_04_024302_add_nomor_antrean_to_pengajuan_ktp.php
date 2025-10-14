<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pengajuan_ktp', function (Blueprint $table) {
            $table->string('nomor_antrean', 3)->nullable()->after('user_id'); 
        });
    }

    public function down()
    {
        Schema::table('pengajuan_ktp', function (Blueprint $table) {
            $table->dropColumn('nomor_antrean');
        });
    }
};
