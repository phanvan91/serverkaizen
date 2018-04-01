<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePhieuSuaChuaTram extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phieu_sua_chuas', function (Blueprint $table) {
            $table->string('ghi_chu')->nullable();
            $table->integer('tram_bao_hanh_id')->unsigned();
            $table->foreign('tram_bao_hanh_id')->references('id')->on('tram_bao_hanhs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phieu_sua_chuas', function (Blueprint $table) {
            $table->dropForeign(['tram_bao_hanh_id']);
            $table->dropColumn('tram_bao_hanh_id');
        });
    }
}
