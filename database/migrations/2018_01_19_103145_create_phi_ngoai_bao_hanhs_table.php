<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhiNgoaiBaoHanhsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phi_ngoai_bao_hanhs', function (Blueprint $table) {
            $table->increments('id');
            $table->float('so_luong')->default(0);

            $table->integer('link_kien_id')->unsigned();
            $table->foreign('link_kien_id')->references('id')->on('linh_kiens');

            $table->integer('phieu_sua_chua_id')->unsigned();
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');

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
        Schema::dropIfExists('phi_ngoai_bao_hanhs');
    }
}
