<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoaiNguoiDungsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_nguoi_dungs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten_loai');
            $table->string('dien_giai')->nullable();

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('loai_nguoi_dung_id')->unsigned();
            $table->foreign('loai_nguoi_dung_id')->references('id')->on('loai_nguoi_dungs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['loai_nguoi_dung_id']);
        });
        Schema::dropIfExists('loai_nguoi_dungs');
    }
}
