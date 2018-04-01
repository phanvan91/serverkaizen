<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinhKienChaConsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linh_kien_cha_cons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('linh_kien_cha_id')->unsigned();
            $table->foreign('linh_kien_cha_id')->references('id')->on('linh_kiens');

            $table->integer('linh_kien_con_id')->unsigned();
            $table->foreign('linh_kien_con_id')->references('id')->on('linh_kiens');

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
        Schema::dropIfExists('linh_kien_cha_cons');
    }
}
