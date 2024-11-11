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
            // Menambahkan kolom jenis_proposal setelah kode_pengajuan
            $table->string('jenis_proposal')->after('kode_pengajuan')->nullable();
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
            // Menghapus kolom jenis_proposal jika rollback
            $table->dropColumn('jenis_proposal');
        });
    }
};
