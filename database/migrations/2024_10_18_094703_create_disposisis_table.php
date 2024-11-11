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
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_surat_id')->constrained('pengajuan_surat')->onDelete('cascade'); // Menghubungkan dengan tabel pengajuan_surats
            $table->string('jabatan');
            $table->string('status')->default('Proses');
            $table->enum('approval', ['-', 'Approve']);
            $table->date('tanggal_terima')->nullable();
            $table->date('tanggal_proses')->nullable();
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
        Schema::dropIfExists('disposisi');
    }
};
