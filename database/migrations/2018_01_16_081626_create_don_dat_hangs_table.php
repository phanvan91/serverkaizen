<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonDatHangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('don_dat_hangs', function (Blueprint $table) {
            $table->increments('id');
            $table->date('ngay_dat_hang');
            $table->string('so_ct');
            $table->integer('nguoi_dat_id');
            $table->string('ly_do');

            $table->integer('trung_tam_bao_hanh_id')->unsigned();
            $table->foreign('trung_tam_bao_hanh_id')->references('id')->on('trung_tam_bao_hanhs');

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
        Schema::dropIfExists('don_dat_hangs');
    }
}
