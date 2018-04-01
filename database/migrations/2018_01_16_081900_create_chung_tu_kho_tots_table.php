<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChungTuKhoTotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chung_tu_kho_tots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cong_ty_id')->unsigned();
            $table->foreign('cong_ty_id')->references('id')->on('cong_ties');
            $table->integer('phieu_sua_chua_id')->unsigned()->nullable();
            $table->integer('phieu_de_nghi_id')->unsigned()->nullable();

            $table->integer('tk_no_id')->unsigned()->nullable();
            $table->integer('tk_co_id')->unsigned()->nullable();
            $table->integer('doi_tuong_no_id')->unsigned()->nullable();
            $table->integer('doi_tuong_co_id')->unsigned()->nullable();

            $table->integer('loai_ct');//3 types: xuat,nhap,chuyen
            $table->date('ngay_ct');
            $table->integer('so_ct');
            $table->integer('ma_ct_id')->unsigned()->nullable();
            $table->date('ngay_nhan')->nullable();//TODO: chuyen kho

            $table->integer('don_dat_hang_id')->unsigned()->nullable();
            $table->foreign('don_dat_hang_id')->references('id')->on('don_dat_hangs');

            $table->string('ten_nguoi_nhan')->nullable();
            $table->string('dia_chi')->nullable();
            $table->string('dien_giai')->nullable();
            $table->string('so_dt')->nullable();
            $table->string('ten_nguoi_giao')->nullable();
            $table->string('so_ct_goc')->nullable();
            $table->date('ngay_ct_goc')->nullable();
            $table->string('don_vi_ct_goc')->nullable();
            $table->integer('model_id')->unsigned()->nullable();
            $table->integer('serial_id')->unsigned()->nullable();

            $table->integer('kho_nhap_id')->unsigned()->nullable();
            $table->foreign('kho_nhap_id')->references('id')->on('khos');
            $table->integer('trung_tam_id')->unsigned()->nullable();

            $table->integer('kho_xuat_id')->unsigned()->nullable();
            $table->foreign('kho_xuat_id')->references('id')->on('khos');
            $table->boolean('tao_tu_dong')->default(false);

            $table->float('tong_so_tien_truoc_thue',12,2)->default(0);
            $table->float('phan_tram_thue')->default(0);

            $table->integer('trang_thai');//cho_xuat_hang    da_xuat_hang  da_huy   da_nhan
            $table->timestamps();
            $table->integer('nguoi_tao_id')->unsigned();
            $table->foreign('nguoi_tao_id')->references('id')->on('users');

            $table->integer('nguoi_sua_id')->unsigned()->nullable();
            $table->foreign('nguoi_sua_id')->references('id')->on('users');

            $table->integer('nguoi_xoa_id')->unsigned()->nullable();
            $table->foreign('nguoi_xoa_id')->references('id')->on('users');

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
        Schema::dropIfExists('chung_tu_kho_tots');
    }
}
