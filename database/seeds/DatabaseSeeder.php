<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TochucsTableSeeder::class);
        $this->call(LoaiNguoiDungsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(HeThongTaiKhoanKeToanTableSeeder::class);
        $this->call(LoaiChungTuTableSeeder::class);
        $this->call(SoHieuChungTusTableSeeder::class);
    }
}
