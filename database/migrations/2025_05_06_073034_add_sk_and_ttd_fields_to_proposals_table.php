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
            $table->boolean('perlu_sk')->nullable()->after('pemohon_id');
            $table->string('pihak_pembuat_sk')->nullable()->after('perlu_sk');
            $table->boolean('perlu_ttd')->nullable()->after('pihak_pembuat_sk');
            $table->json('pihak_ttd')->nullable()->after('perlu_ttd');
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
                'perlu_sk',
                'pihak_pembuat_sk',
                'perlu_ttd',
                'pihak_ttd',
            ]);
        });
    }
};
