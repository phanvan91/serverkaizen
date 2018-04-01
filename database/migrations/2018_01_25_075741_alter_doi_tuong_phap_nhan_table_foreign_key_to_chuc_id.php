<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDoiTuongPhapNhanTableForeignKeyToChucId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('doi_tuong_phap_nhans', function (Blueprint $table) {
//            $table->integer('to_chuc_id')->unsigned();
//            $table->foreign('to_chuc_id')->references('id')->on('to_chucs');
//            $table->softDeletes();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('doi_tuong_phap_nhans', function (Blueprint $table) {
//            $table->dropForeign(['to_chuc_id']);
//        });
    }
}
