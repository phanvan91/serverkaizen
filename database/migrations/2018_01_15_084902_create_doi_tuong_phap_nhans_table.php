<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoiTuongPhapNhansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doi_tuong_phap_nhans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ma');
            $table->string('ten');

            $table->integer('goc_id')->nullable();
            $table->integer('loai');//user/tram/trung tam/khach hang/nha cung ung/khac
            $table->integer('to_chuc_id')->unsigned();
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
        Schema::dropIfExists('doi_tuong_phap_nhans');
    }
}
