<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhieuSuaChuaTinhTrangHuHongTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phieusuachua_tinhtranghuhong', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('phieu_sua_chua_id')->unsigned();
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');

            $table->integer('tinh_trang_hu_hong_id')->unsigned();
            $table->foreign('tinh_trang_hu_hong_id')->references('id')->on('tinh_trang_hu_hongs');
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
        Schema::dropIfExists('phieu_sua_chua_tinh_trang_hu_hong');
    }
}
