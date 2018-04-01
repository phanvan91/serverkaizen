<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKhachHangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khach_hangs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ten');
            $table->string('ma');
            $table->string('loai')->nullable();
            $table->string('dien_thoai');
            $table->string('email')->nullable();
            $table->integer('tinh_tp');
            $table->integer('quan_huyen');
            $table->string('dia_chi');

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');

            $table->timestamps();
        });

        Schema::table('cau_tra_lois', function (Blueprint $table) {
            $table->integer('khach_hang_id')->unsigned();
            $table->foreign('khach_hang_id')->references('id')->on('khach_hangs');
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
            $table->dropForeign('khach_hang_id');
        });

        Schema::dropIfExists('khach_hangs');

    }
}
