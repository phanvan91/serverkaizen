<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial')->unique();
            $table->integer('trang_thai');
            $table->integer('model_id')->unsigned();
            $table->foreign('model_id')->references('id')->on('models');

            $table->integer('san_pham_id')->unsigned();
            $table->foreign('san_pham_id')->references('id')->on('san_phams');

            $table->integer('nganh_hang_id')->unsigned();
            $table->foreign('nganh_hang_id')->references('id')->on('nganh_hangs');


            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');

            $table->integer('khach_hang_id')->unsigned()->nullable();
            $table->foreign('khach_hang_id')->references('id')->on('khach_hangs');

            $table->date('ngay_san_xuat')->nullable();
            $table->date('ngay_xuat_kho')->nullable();
            $table->date('ngay_kich_hoat_bh')->nullable();
            $table->date('ngay_het_han')->nullable();

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
        Schema::dropIfExists('serials');
    }
}
