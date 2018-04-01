<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBangTinhCongSuaChuas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('bang_tinh_cong_sua_chuas', function (Blueprint $table) {
//            $table->integer('phieu_sua_chua_id')->unsigned();
//            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bang_tinh_cong_sua_chuas', function (Blueprint $table) {
            $table->dropForeign('phieu_sua_chua_id');
        });
    }
}
