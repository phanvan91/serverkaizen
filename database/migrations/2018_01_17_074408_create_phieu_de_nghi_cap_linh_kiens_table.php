<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhieuDeNghiCapLinhKiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phieu_de_nghi_cap_linh_kiens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('phieu_sua_chua_id')->unsigned()->nullable();
            $table->date('thoi_han_can_vat_tu');

            $table->integer('cong_ty_id')->unsigned();
            $table->foreign('cong_ty_id')->references('id')->on('cong_ties');
            $table->integer('kho_tram_id')->unsigned();
            $table->foreign('kho_tram_id')->references('id')->on('khos');
            $table->integer('kho_trung_tam_id')->unsigned();
            $table->foreign('kho_trung_tam_id')->references('id')->on('khos');

            $table->string('ly_do')->nullable();
            $table->integer('trang_thai')->default(1);
            //de nghi, da cap phat, tu choi, dong y cap linh kien
            //gui de nghi ve trung tam, trung tam da chuyen kho, linh kien da ve tram

            $table->integer('phieu_chuyen_kho_id')->unsigned()->nullable();
            $table->integer('phieu_xuat_kho_id')->unsigned()->nullable();
            $table->foreign('phieu_chuyen_kho_id')->references('id')->on('chung_tu_kho_tots');
            $table->foreign('phieu_xuat_kho_id')->references('id')->on('chung_tu_kho_tots');

            $table->integer('nguoi_tao_id')->unsigned();
            $table->foreign('nguoi_tao_id')->references('id')->on('users');

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
        Schema::dropIfExists('phieu_de_nghi_cap_linh_kiens');
    }
}
