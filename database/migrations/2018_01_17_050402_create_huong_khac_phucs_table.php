<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuongKhacPhucsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('huong_khac_phucs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ma_huong_khac_phuc');
            $table->text('mo_ta');

            $table->integer('nganh_hang_id')->unsigned();
            $table->foreign('nganh_hang_id')->references('id')->on('nganh_hangs');

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');
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
        Schema::dropIfExists('huong_khac_phucs');
    }
}
