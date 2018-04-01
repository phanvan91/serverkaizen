<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTramBaoHanhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tram_bao_hanhs', function (Blueprint $table) {
            $table->string('so_dien_thoai')->nullable();
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
            $table->dropColumn(['so_dien_thoai']);
        });
    }
}
