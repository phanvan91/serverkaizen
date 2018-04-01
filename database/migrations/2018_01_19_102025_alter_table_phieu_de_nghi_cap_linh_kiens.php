<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePhieuDeNghiCapLinhKiens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phieu_de_nghi_cap_linh_kiens', function (Blueprint $table) {
            $table->foreign('phieu_sua_chua_id')->references('id')->on('phieu_sua_chuas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phieu_de_nghi_cap_linh_kiens', function (Blueprint $table) {
            $table->dropForeign('phieu_sua_chua_id');
        });
    }
}
