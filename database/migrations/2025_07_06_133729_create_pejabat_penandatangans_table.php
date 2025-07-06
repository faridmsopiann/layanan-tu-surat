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
        Schema::create('pejabat_penandatangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip');
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans')->onDelete('set null');
            $table->string('ttd')->nullable(); 
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
        Schema::dropIfExists('pejabat_penandatangans');
    }
};
