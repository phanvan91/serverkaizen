<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDonDatHangChiTietForeignKay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dat_hang_chi_tiets', function (Blueprint $table) {
            $table->integer('don_dat_hang_id')->unsigned();
            $table->foreign('don_dat_hang_id')->references('id')->on('don_dat_hangs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dat_hang_chi_tiets', function (Blueprint $table) {
            $table->dropForeign(['don_dat_hang_id']);
        });
    }
}
