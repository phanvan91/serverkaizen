<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDanhSachChiPhiDiPhieuSuaChua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dscpdl_psc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('phieu_sua_chua_id')->unsigned();
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');
            $table->integer('danh_sach_chi_phi_di_lai_id')->unsigned();
            $table->foreign('danh_sach_chi_phi_di_lai_id')->references('id')->on('danh_sach_chi_phi_di_lais');
            $table->text('ghi_chu')->nullable();
            $table->string('lat');
            $table->string('lng');
            $table->float('tong_tien', 14, 2);
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
        //
    }
}
