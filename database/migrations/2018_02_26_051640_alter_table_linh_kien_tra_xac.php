<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLinhKienTraXac extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('linh_kiens', function (Blueprint $table) {
            $table->boolean('tra_xac')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('linh_kiens', function (Blueprint $table) {
            $table->dropColumn('tra_xac');
        });
    }
}
