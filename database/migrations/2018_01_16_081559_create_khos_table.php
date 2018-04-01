<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten_kho');
            $table->string('ma_kho');
            $table->integer('loai_kho');//tot, xau, thanh pham

            $table->integer('tram_bao_hanh_id')->unsigned()->nullable();
            $table->foreign('tram_bao_hanh_id')->references('id')->on('tram_bao_hanhs');
            $table->string('dia_chi')->nullable();
            $table->integer('trung_tam_bao_hanh_id')->unsigned();
            $table->foreign('trung_tam_bao_hanh_id')->references('id')->on('trung_tam_bao_hanhs');

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');

            $table->integer('cong_ty_id')->unsigned();
            $table->foreign('cong_ty_id')->references('id')->on('cong_ties');
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
        Schema::dropIfExists('khos');
    }
}
