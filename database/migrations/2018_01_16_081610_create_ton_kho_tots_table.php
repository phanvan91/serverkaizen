<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTonKhoTotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ton_kho_tots', function (Blueprint $table) {
            $table->integer('ton_dau')->unsigned()->default(0);
            $table->integer('nhap_kho')->unsigned()->default(0);
            $table->integer('xuat_bh')->unsigned()->default(0);
            $table->integer('xuat_ngoai_bh')->unsigned()->default(0);
            $table->integer('xuat_noi_bo')->unsigned()->default(0);
            $table->integer('ton_cuoi')->unsigned()->default(0);
            $table->integer('so_luong_cho_xuat')->unsigned()->default(0);
            $table->integer('so_luong_cho_nhap')->unsigned()->default(0);

            $table->integer('kho_id')->unsigned();
            $table->integer('linh_kien_id')->unsigned();
            $table->foreign('linh_kien_id')->references('id')->on('linh_kiens');
            $table->foreign('kho_id')->references('id')->on('khos');
            $table->primary(array('kho_id', 'linh_kien_id'));

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
        Schema::dropIfExists('ton_kho_tots');
    }
}
