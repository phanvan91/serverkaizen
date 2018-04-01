<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCauTraLoiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cau_tra_lois', function (Blueprint $table) {
            $table->integer('phieu_sua_chua_id')->unsigned();
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');
            $table->integer('user_id')->unsigned();

        });
        Schema::table('cau_hois', function (Blueprint $table) {
            $table->integer('loai')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cau_tra_lois', function (Blueprint $table) {
            $table->dropForeign(['phieu_sua_chua_id']);
        });
    }
}
