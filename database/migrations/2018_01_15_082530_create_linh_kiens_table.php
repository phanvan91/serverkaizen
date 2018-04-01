<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinhKiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linh_kiens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ma');
            $table->string('ten');
            $table->float('gia_ban',12,2)->default(0);
            $table->string('don_vi');
            $table->integer('thang_gia_han_sau_bao_hanh')->default(0);
            $table->boolean('linh_kien_ao')->default(false);
            $table->integer('nhom_linh_kien_id')->unsigned()->nullable();
            $table->foreign('nhom_linh_kien_id')->references('id')->on('nhom_linh_kiens');

            $table->integer('san_pham_id')->unsigned()->nullable();
            $table->foreign('san_pham_id')->references('id')->on('san_phams');
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
        Schema::dropIfExists('linh_kiens');
    }
}
