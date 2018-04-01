<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoaiChungTusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loai_chung_tus', function (Blueprint $table) {
            $table->increments('id');

            $table->string('loai_chung_tu');
            $table->string('ten_chung_tu');
            $table->string('muc_dich_su_dung');

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');

            $table->softDeletes();
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
        Schema::dropIfExists('loai_chung_tus');
    }
}
