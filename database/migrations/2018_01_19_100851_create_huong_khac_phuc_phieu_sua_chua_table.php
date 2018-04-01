<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHuongKhacPhucPhieuSuaChuaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('huong_khac_phuc_phieu_sua_chua', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('phieu_sua_chua_id')->unsigned();
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');

            $table->integer('huong_khac_phuc_id')->unsigned();
            $table->foreign('huong_khac_phuc_id')->references('id')->on('huong_khac_phucs');
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
        Schema::dropIfExists('huong_khac_phuc_phieu_sua_chua');
    }
}
