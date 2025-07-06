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
        Schema::create('proposal_penugasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai_penugasans')->onDelete('set null');
            $table->string('nama_manual')->nullable();
            $table->foreignId('peran_tugas_id')->nullable()->constrained('perans')->onDelete('set null');
            $table->string('unit_asal')->nullable(); 
            $table->string('jabatan')->nullable(); 
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
        Schema::dropIfExists('proposal_penugasans');
    }
};
