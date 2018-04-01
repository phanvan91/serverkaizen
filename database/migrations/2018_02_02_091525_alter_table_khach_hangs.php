<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableKhachHangs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
                   ALTER TABLE `khach_hangs` 
        CHANGE COLUMN `tinh_tp` `tinh_tp` VARCHAR(255) NULL DEFAULT NULL ,
        CHANGE COLUMN `quan_huyen` `quan_huyen` VARCHAR(255) NULL DEFAULT NULL 
        ");

        Schema::table('khach_hangs', function (Blueprint $table) {

            $table->string('phuong_xa')->nullable();

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
