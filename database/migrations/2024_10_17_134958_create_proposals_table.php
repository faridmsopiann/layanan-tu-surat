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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda')->nullable();
            $table->date('tanggal_surat');
            $table->string('nomor_surat')->nullable();
            $table->string('asal_surat');
            $table->string('hal');
            $table->date('diterima_tanggal')->nullable();
            $table->string('untuk')->nullable();
            $table->enum('status_disposisi', [
                'Memproses',
                'Menunggu Approval Dekan',
                'Approved Dekan',
                'Menunggu Approval Kabag',
                'Approved Kabag TU',
                'Menunggu Approval Keuangan',
                'Approved Keuangan',
                'Selesai',
                'Ditolak',
            ])->default('Memproses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposals');
    }
};
