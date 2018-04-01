<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNhapXuatXacsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nhap_xuat_xacs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ton_dau_ky');
            $table->integer('chung_tu_id')->unsigned();
            $table->foreign('chung_tu_id')->references('id')->on('chung_tu_kho_xacs');

            $table->integer('linh_kien_id')->unsigned();
            $table->foreign('linh_kien_id')->references('id')->on('linh_kiens');
            $table->date('ngay_ct');
            $table->integer('so_luong_yc');
            $table->integer('loai_giao_dich');//-1: xuat 1: nhap
            $table->integer('so_luong_thuc');
            $table->integer('so_luong_nhan')->nullable();//for chuyen kho only
            $table->integer('loai_ct')->unsigned();//3 types: xuat,nhap,chuyen
            $table->float('don_gia',10,2);
            $table->integer('ton_cuoi_ky');
            $table->integer('kho_id');
            $table->string('ghi_chu')->default('');
            $table->boolean('da_duyet')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nhap_xuat_xacs');
    }
}
