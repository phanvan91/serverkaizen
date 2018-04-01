<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTramBaoHanhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tram_bao_hanhs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ma');
            $table->string('ten');
            $table->string('dia_chi');
            $table->string('tinh')->nullable();
            $table->integer('loai_tram')->unsigned();//2 loai: tinh gian/tram trung tam
            $table->integer('cong_ty_id')->unsigned()->nullable();;
            $table->foreign('cong_ty_id')->references('id')->on('cong_ties');

            $table->integer('trung_tam_bao_hanh_id')->unsigned();
            $table->foreign('trung_tam_bao_hanh_id')->references('id')->on('trung_tam_bao_hanhs');

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');
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
        Schema::dropIfExists('tram_bao_hanhs');
    }
}
