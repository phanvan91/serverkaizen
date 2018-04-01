<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaoSoChungTuKhoXacTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER `trigger_create_so_chung_tu_kho_xac_id` BEFORE INSERT ON `chung_tu_kho_xacs` FOR EACH ROW BEGIN
          DECLARE nseq INT;
          SELECT  COALESCE(MAX(so_ct), 0) + 1
          INTO    nseq
          FROM    chung_tu_kho_xacs
          WHERE   loai_ct = NEW.loai_ct AND
          date(ngay_ct) = month(NEW.ngay_ct) AND
          month(ngay_ct) = month(NEW.ngay_ct) AND
		  year(ngay_ct) = month(NEW.ngay_ct);
          SET NEW.so_ct = nseq;
          END;
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER `trigger_create_so_chung_tu_kho_xac_id`');
    }
}
