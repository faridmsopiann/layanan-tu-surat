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
        Schema::create('modal_disposisi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id'); // Relasi ke tabel proposals
            $table->string('tujuan')->nullable();
            $table->enum('status', ['Disetujui', 'Ditolak', 'Diproses'])->default('Diproses');
            $table->date('tanggal_diterima')->nullable();
            $table->date('tanggal_proses')->nullable();
            $table->string('diverifikasi_oleh')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps(); // created_at and updated_at timestamps

            // Foreign key constraint untuk proposal_id
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modal_disposisi');
    }
};
