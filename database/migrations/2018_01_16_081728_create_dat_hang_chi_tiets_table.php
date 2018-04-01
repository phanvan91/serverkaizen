<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatHangChiTietsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dat_hang_chi_tiets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('linh_kien_id')->unsigned();
            $table->integer('so_luong');
            $table->foreign('linh_kien_id')->references('id')->on('linh_kiens');

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
        Schema::dropIfExists('dat_hang_chi_tiets');
    }
}
