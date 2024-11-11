<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan');
            $table->timestamps();
        });

        // Insert predefined jabatan values
        DB::table('jabatan')->insert([
            ['nama_jabatan' => 'Staff Tata Usaha'],
            ['nama_jabatan' => 'Kabag Tata Usaha'],
            ['nama_jabatan' => 'Staff Dekanat'],
            ['nama_jabatan' => 'Dekan'],
            ['nama_jabatan' => 'Wadek Kemahasiswaan'],
            ['nama_jabatan' => 'Wadek Akademik'],
            ['nama_jabatan' => 'Wadek Administrasi Umum'],
            ['nama_jabatan' => 'Staff Keuangan'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jabatan');
    }
};
