<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoHieuChungTusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('so_hieu_chung_tus', function (Blueprint $table) {
            $table->increments('id');

            $table->string('so_hieu_chung_tu');
            $table->string('ten_chung_tu');
            $table->string('muc_dich_su_dung')->nullable();
            $table->integer('tai_khoang_no_id')->unsigned()->nullable();
            $table->foreign('tai_khoang_no_id')->references('id')->on('he_thong_tai_khoang_ke_toans');

            $table->integer('tai_khoang_co_id')->unsigned()->nullable();
            $table->foreign('tai_khoang_co_id')->references('id')->on('he_thong_tai_khoang_ke_toans');

            $table->integer('loai_chung_tu_id')->unsigned();
            $table->foreign('loai_chung_tu_id')->references('id')->on('loai_chung_tus');

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
        Schema::dropIfExists('so_hieu_chung_tus');
    }
}
