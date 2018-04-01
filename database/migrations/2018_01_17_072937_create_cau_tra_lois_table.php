<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCauTraLoisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cau_tra_lois', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cau_tra_loi');
            $table->tinyInteger('da_thuc_hien')->default(0);

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');

            $table->integer('cau_hoi_id')->unsigned();
            $table->foreign('cau_hoi_id')->references('id')->on('cau_hois');

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
        Schema::dropIfExists('cau_tra_lois');
    }
}
