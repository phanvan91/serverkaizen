<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhieuSuaChuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phieu_sua_chuas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kenh_tiep_nhan');
            $table->integer('uu_tien');
            $table->integer('loai_hinh_dv');
            $table->date('ngay_tiep_nhan');
            $table->integer('noi_thuc_hien');
            $table->date('ngay_hoan_tat_mong_muon');
            $table->text('thong_tin_dich_vu_ghi_chu')->nullable();
            $table->text('hinh_anh')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('khach_hang_id')->unsigned();
            $table->foreign('khach_hang_id')->references('id')->on('khach_hangs');

            $table->integer('serial_id')->unsigned();
            $table->foreign('serial_id')->references('id')->on('serials');

//            $table->integer('cau_tra_loi_id')->unsigned();
//            $table->foreign('cau_tra_loi_id')->references('id')->on('cau_tra_lois');

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');

            $table->tinyInteger('status')->default(0);

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
        Schema::dropIfExists('phieu_sua_chuas');
    }
}
