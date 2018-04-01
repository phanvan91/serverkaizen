<?php

use Illuminate\Database\Seeder;

class TochucsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('to_chucs')->insert(
            [
                'ten' => 'Tổ chức 1',
                'ngay_bd' => date("Y-m-d"),
                'ngay_kt' => '2020-12-31',
                'created_at' => date("Y-m-d")
            ]
        );
    }
}
