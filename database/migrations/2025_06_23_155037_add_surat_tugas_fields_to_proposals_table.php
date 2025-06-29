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
            $table->unsignedBigInteger('jenis_kegiatan_id')->nullable()->after('hal');
            $table->date('tanggal_mulai')->nullable()->after('jenis_kegiatan_id');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->string('lokasi_kegiatan')->nullable()->after('tanggal_selesai');
            $table->json('instansi_terkait')->nullable()->after('lokasi_kegiatan');
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
            $table->dropColumn([
                'jenis_kegiatan_id',
                'tanggal_mulai',
                'tanggal_selesai',
                'lokasi_kegiatan',
                'instansi_terkait',
            ]);
        });
    }
};
