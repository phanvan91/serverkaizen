<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePhieuSuaChuaLinhKiens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psc_lks', function (Blueprint $table) {
            $table->integer('phieu_sua_chua_id')->unsigned();
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');
            $table->integer('linh_kien_id')->unsigned();
            $table->foreign('linh_kien_id')->references('id')->on('linh_kiens');
            $table->integer('so_luong_cap')->unsigned();
            $table->integer('so_luong_tra')->unsigned()->nullable();
            $table->float('don_gia')->default(0);
            $table->primary(array('phieu_sua_chua_id', 'linh_kien_id'));
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
        Schema::dropIfExists('psc_lks');
    }
}
