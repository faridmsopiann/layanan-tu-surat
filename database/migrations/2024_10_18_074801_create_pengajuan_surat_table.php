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
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemohon_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_surat');
            $table->string('asal_surat');
            $table->string('hal');
            $table->string('status')->default('Memproses');
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
        Schema::dropIfExists('pengajuan_surat');
    }
};
