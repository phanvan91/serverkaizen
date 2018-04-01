<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBangTinhCongSuaChuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bang_tinh_cong_sua_chuas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ma_huong_khac_phuc');
            $table->string('ten_huong_khac_phuc');
            $table->float('don_gia', 14 , 2);

            $table->integer('trung_tam_bao_hanh_id')->unsigned();
            $table->foreign('trung_tam_bao_hanh_id')->references('id')->on('trung_tam_bao_hanhs');

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');

            $table->softDeletes();
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
        Schema::dropIfExists('bang_tinh_cong_sua_chuas');
    }
}
