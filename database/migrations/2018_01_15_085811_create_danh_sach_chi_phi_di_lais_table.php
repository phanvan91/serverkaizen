<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDanhSachChiPhiDiLaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('danh_sach_chi_phi_di_lais', function (Blueprint $table) {
            $table->increments('id');

            $table->string('thanh_pho');
            $table->string('quan');
            $table->string('phuong');
            $table->float('km_mot_chieu');
            $table->float('km_khu_hoi');
            $table->float('don_gia', 15, 2);
            $table->float('thanh_tien_mot', 15, 2);
            $table->float('thanh_tien_hai', 15, 2);

            $table->string('ten_thanh_pho')->nullable();
            $table->string('ten_quan')->nullable();
            $table->string('ten_phuong')->nullable();

            $table->integer('trung_tam_bao_hanh_id')->unsigned()->nullable();
            $table->foreign('trung_tam_bao_hanh_id')->references('id')->on('trung_tam_bao_hanhs');

            $table->integer('tram_bao_hanh_id')->unsigned();
            $table->foreign('tram_bao_hanh_id')->references('id')->on('tram_bao_hanhs');

            $table->integer('to_chuc_id')->unsigned();
            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');

            $table->integer('cong_ty_id')->unsigned()->nullable();
            $table->foreign('cong_ty_id')->references('id')->on('cong_ties');

            $table->softDeletes();
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
        Schema::dropIfExists('danh_sach_chi_phi_di_lais');
    }
}
