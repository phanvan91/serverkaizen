<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTraLinhKienChiTietsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tra_linh_kien_chi_tiets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('linh_kien_id')->unsigned();
            $table->foreign('linh_kien_id')->references('id')->on('linh_kiens');

            $table->integer('phieu_tra_linh_kien_id')->unsigned();
            $table->foreign('phieu_tra_linh_kien_id')->references('id')->on('phieu_tra_linh_kiens');

            $table->integer('so_luong');
            $table->integer('so_luong_thuc_nhan');
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
        Schema::dropIfExists('tra_linh_kien_chi_tiets');
    }
}
