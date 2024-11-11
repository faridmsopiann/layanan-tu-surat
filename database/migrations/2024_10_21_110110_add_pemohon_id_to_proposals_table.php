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
            // Membuat relasi foreign key dengan tabel users
            $table->foreignId('pemohon_id')->constrained('users')->onDelete('cascade');  // Set null jika user dihapus
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
            // Menghapus foreign key dan kolom pemohon_id
            $table->dropForeign(['pemohon_id']);
            $table->dropColumn('pemohon_id');
        });
    }
};
