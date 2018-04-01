<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNguyenNhanPhieuSuaChuaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nguyennhan_phieusuachua', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('phieu_sua_chua_id')->unsigned();
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');

            $table->integer('nguyen_nhan_id')->unsigned();
            $table->foreign('nguyen_nhan_id')->references('id')->on('nguyen_nhans');
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
        Schema::dropIfExists('nguyen_nhan_phieu_sua_chua');
    }
}
