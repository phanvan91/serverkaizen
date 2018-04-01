<?php

use Illuminate\Database\Seeder;

class HeThongTaiKhoanKeToanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listTaiKhoanKeToan = [
            [
              'so_hieu_tai_khoang' => '1111', 'ten_tai_khoang' => 'Tiền Việt Nam: Phản ánh tình hình thu, chi, tồn quỹ tiền Việt Nam tại quỹ tiền mặt', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
              'so_hieu_tai_khoang' => '152', 'ten_tai_khoang' => 'Nguyên liệu, vật liệu', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
              'so_hieu_tai_khoang' => '1561', 'ten_tai_khoang' => 'Giá mua hàng hóa', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
              'so_hieu_tai_khoang' => '1381', 'ten_tai_khoang' => 'Tài sản thiếu chờ xử lý', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
              'so_hieu_tai_khoang' => '331', 'ten_tai_khoang' => 'Phải trả cho người bán', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
              'so_hieu_tai_khoang' => '3381', 'ten_tai_khoang' => 'Tài sản thừa chờ giải quyết', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_tai_khoang' => '131', 'ten_tai_khoang' => 'Phải thu của khách hàng', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
        ];

        DB::table('he_thong_tai_khoang_ke_toans')->insert($listTaiKhoanKeToan);
    }
}
