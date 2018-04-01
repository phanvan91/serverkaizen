<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDanhSachChiPhiDiLaiPhieuSuaChua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('danh_sach_chi_phi_di_lais', function (Blueprint $table) {
//            $table->integer('phieu_sua_chua_id')->unsigned();
//            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('danh_sach_chi_phi_di_lais', function (Blueprint $table) {
//            $table->dropForeign('phieu_sua_chua_id');
//        });
    }
}
