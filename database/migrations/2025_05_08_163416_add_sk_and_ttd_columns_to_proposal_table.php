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
            $table->string('soft_file_sk')->nullable()->after('soft_file');
            $table->boolean('sudah_ttd')->nullable()->after('soft_file_sk');
            $table->boolean('sudah_sk')->nullable()->after('sudah_ttd');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposal', function (Blueprint $table) {
            $table->dropColumn(['soft_file_sk', 'sudah_ttd', 'sudah_sk']);
        });
    }
};
