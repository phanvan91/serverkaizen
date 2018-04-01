<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AfterTramBaoHanh1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tram_bao_hanhs', function (Blueprint $table) {
            $table->string('don_vi_van_chuyen')->nullable();
            $table->string('nguoi_dai_dien')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tram_bao_hanhs', function (Blueprint $table) {
            $table->dropColumn('don_vi_van_chuyen');
            $table->dropColumn('nguoi_dai_dien');
        });
    }
}
