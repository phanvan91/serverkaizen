<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePhieuNhapKho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phieu_nhap_kho', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ton_dau_ky');
            $table->integer('chung_tu_kho_tot_id')->unsigned();
            $table->foreign('chung_tu_kho_tot_id')->references('id')->on('chung_tu_kho_tots');

            $table->integer('serial_id')->unsigned();
            $table->foreign('serial_id')->references('id')->on('serials');

            $table->integer('so_luong_yc');
            $table->integer('loai_giao_dich');//-1: xuat 1: nhap
            $table->integer('so_luong_thuc');
            $table->integer('ton_cuoi_ky');
            $table->integer('kho_id');
            $table->string('ghi_chu')->default('');
            $table->boolean('da_duyet')->default(false);
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
        Schema::dropIfExists('phieu_nhap_kho');
    }
}
