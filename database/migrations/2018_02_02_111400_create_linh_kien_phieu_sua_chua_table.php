<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinhKienPhieuSuaChuaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linh_kien_phieu_sua_chua', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('linh_kien_id')->unsigned();
            $table->foreign('linh_kien_id')->references('id')->on('linh_kiens');
            $table->integer('phieu_sua_chua_id')->unsigned();
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');
            $table->float('so_luong');
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
        Schema::dropIfExists('linh_kien_phieu_sua_chua');
    }
}
