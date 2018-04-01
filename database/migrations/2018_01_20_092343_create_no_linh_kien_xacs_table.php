<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoLinhKienXacsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('no_linh_kien_xacs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nhan_vien_id')->unsigned();
            $table->integer('tram_bao_hanh_id')->unsigned();
            $table->foreign('tram_bao_hanh_id')->references('id')->on('tram_bao_hanhs');
            //$table->integer('kho_id')->unsigned();
            //$table->foreign('kho_id')->references('id')->on('khos');
            $table->integer('phieu_sua_chua_id')->unsigned();
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');
            $table->integer('chung_tu_id')->unsigned();
            $table->foreign('chung_tu_id')->references('id')->on('chung_tu_kho_tots');

            $table->integer('so_luong_cap');
            $table->integer('linh_kien_cap_id')->unsigned();
            $table->foreign('linh_kien_cap_id')->references('id')->on('linh_kiens');

            $table->integer('so_luong_thu');
            $table->integer('linh_kien_thu_hoi_id')->unsigned();
            $table->foreign('linh_kien_thu_hoi_id')->references('id')->on('linh_kiens');
            $table->boolean('hoan_thanh_tra_xac')->default(false);
            $table->string('ghi_chu')->nullable();
            $table->integer('trang_thai')->unsigned()->default(0);//1: da tra cho nhap kho, 2 da nhap kho
            //1: allow edit, 2: not allow edit
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
        Schema::dropIfExists('no_linh_kien_xacs');
    }
}
