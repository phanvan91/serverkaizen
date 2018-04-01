<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTinhCongSuaChuaPhieuSuaChua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('btcsc_psc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bang_tinh_cong_sua_chua_id')->unsigned();
            $table->foreign('bang_tinh_cong_sua_chua_id')->references('id')->on('bang_tinh_cong_sua_chuas');
            $table->integer('phieu_sua_chua_id')->unsigned();
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');
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
        //
    }
}
