<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Menambahkan kolom kode_pengajuan setelah kolom id
            $table->string('kode_pengajuan')->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Menghapus kolom kode_pengajuan jika rollback migrasi
            $table->dropColumn('kode_pengajuan');
        });
    }
};
