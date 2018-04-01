<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPhieuSuaChua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phieu_sua_chuas', function (Blueprint $table) {
            $table->integer('nhan_vien_bao_hanh_id')->unsigned()->nullable();

            $table->integer('phieu_nhap_kho_id')->unsigned()->nullable();
            $table->foreign('phieu_nhap_kho_id')->references('id')->on('phieu_nhap_kho');
            $table->float('tong_tien')->nullable();
            $table->boolean('tra_xac_linh_kien')->default(true);


        });
        Schema::table('dscpdl_psc', function (Blueprint $table) {
            $table->text('address')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phieu_sua_chuas', function (Blueprint $table) {
            $table->dropForeign(['phieu_nhap_kho_id']);
        });
    }
}
