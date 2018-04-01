<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('dien_thoai')->nullable();
            $table->integer('trung_tam_bao_hanh_id')->unsigned()->nullable();
            $table->foreign('trung_tam_bao_hanh_id')->references('id')->on('trung_tam_bao_hanhs');

            $table->integer('tram_bao_hanh_id')->unsigned()->nullable();
            $table->foreign('tram_bao_hanh_id')->references('id')->on('tram_bao_hanhs');

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
           $table->dropForeign(['trung_tam_bao_hanh_id']);
           $table->dropForeign(['tram_bao_hanh_id']);
        });
    }
}
