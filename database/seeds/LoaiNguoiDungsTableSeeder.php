<?php

use Illuminate\Database\Seeder;

class LoaiNguoiDungsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listLoaiNguoiDungs = [
            [
                'ten_loai' => 'admin', 'dien_giai' => '' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'ten_loai' => 'QUANTRI', 'dien_giai' => 'Quản trị hệ thống' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'ten_loai' => 'KETOAN', 'dien_giai' => 'Kế toán trưởng, Kế toán trung tâm, Kế toán viên', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'ten_loai' => 'NVBH', 'dien_giai' => 'Trưởng phòng, Nhân viên bảo hành', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'ten_loai' => 'CSKH', 'dien_giai' => 'Trưởng phòng CSKH, Nhân viên CSKH', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'ten_loai' => 'QUANLY', 'dien_giai' => 'Trưởng trung tâm, trưởng trạm, trưởng nhóm bảo hành', 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ]
        ];

        DB::table('loai_nguoi_dungs')->insert($listLoaiNguoiDungs);
    }
}
