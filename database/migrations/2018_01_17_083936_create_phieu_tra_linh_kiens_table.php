<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhieuTraLinhKiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phieu_tra_linh_kiens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('loai_phieu');//tra LK xac, tot
            $table->integer('phieu_sua_chua_id')->unsigned()->nullable();

            $table->integer('nguoi_tao_id')->unsigned();
            $table->integer('phieu_nhap_kho_id')->unsigned();

            $table->integer('tram_bao_hanh_id')->unsigned();
            $table->foreign('tram_bao_hanh_id')->references('id')->on('tram_bao_hanhs');

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
        Schema::dropIfExists('phieu_tra_linh_kiens');
    }
}
