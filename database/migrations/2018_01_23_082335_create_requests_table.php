<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nguoi_gui_id')->unsigned();
            $table->integer('ben_nhan_id')->unsigned();
            $table->integer('ben_nhan_la_nhom')->unsigned();
            $table->integer('tram_bao_hanh_id')->unsigned()->nullable();
            $table->integer('trung_tam_bao_hanh_id')->unsigned()->nullable();
            $table->boolean('da_xem')->default(false);
            $table->boolean('da_xu_ly')->default(false);
            $table->integer('doi_tuong');
            $table->integer('doi_tuong_id');
            $table->string('ghi_chu')->nullable();

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
        Schema::dropIfExists('requests');
    }
}
