<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeNghiCapLinhKienChiTietsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('de_nghi_cap_linh_kien_chi_tiets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('phieu_de_nghi_id')->unsigned();
            $table->foreign('phieu_de_nghi_id')->references('id')->on('phieu_de_nghi_cap_linh_kiens');
            $table->integer('linh_kien_id')->unsigned();
            $table->foreign('linh_kien_id')->references('id')->on('linh_kiens');
            $table->integer('so_luong')->unsigned()->default(0);
            $table->integer('so_luong_gui_trung_tam')->unsigned()->default(0);
            $table->float('don_gia',10,2);
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
        Schema::dropIfExists('de_nghi_cap_linh_kien_chi_tiets');
    }
}
