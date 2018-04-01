<?php

use Illuminate\Database\Seeder;

class LoaiChungTuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listChungTus = [
            [
                'loai_chung_tu' => 'PNKLKT',
                'ten_chung_tu' => 'Phiếu nhập kho linh kiện tốt',
                'muc_dich_su_dung' => 'Phiếu nhập kho linh kiện tốt' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'loai_chung_tu' => 'PXKLKT',
                'ten_chung_tu' => 'Phiếu xuất kho linh kiện tốt',
                'muc_dich_su_dung' => 'Phiếu xuất kho linh kiện tốt' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'loai_chung_tu' => 'PCKLKT',
                'ten_chung_tu' => 'Phiếu chuyển kho linh kiện tốt',
                'muc_dich_su_dung' => 'Phiếu chuyển kho linh kiện tốt' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'loai_chung_tu' => 'PNKLKX',
                'ten_chung_tu' => 'Phiếu nhập kho linh kiện xác',
                'muc_dich_su_dung' => 'Phiếu nhập kho linh kiện xác' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'loai_chung_tu' => 'PXKLKX',
                'ten_chung_tu' => 'Phiếu xuất kho linh kiện xác',
                'muc_dich_su_dung' => 'Phiếu xuất kho linh kiện xác' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'loai_chung_tu' => 'PCKLKX',
                'ten_chung_tu' => 'Phiếu chuyển kho linh kiện xác',
                'muc_dich_su_dung' => 'Phiếu chuyển kho linh kiện xác' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'loai_chung_tu' => 'PNKTPBH',
                'ten_chung_tu' => 'Phiếu nhập kho thành phẩm bảo hành',
                'muc_dich_su_dung' => 'Phiếu nhập kho thành phẩm bảo hành' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'loai_chung_tu' => 'PXKTPBH',
                'ten_chung_tu' => 'Phiếu nhập kho thành phẩm bảo hành',
                'muc_dich_su_dung' => 'Phiếu nhập kho thành phẩm bảo hành' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'loai_chung_tu' => 'PCKTPBH',
                'ten_chung_tu' => 'Phiếu chuyển kho thành phẩm bảo hành',
                'muc_dich_su_dung' => 'Phiếu chuyển kho thành phẩm bảo hành' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ],
            [
                'loai_chung_tu' => 'PSC',
                'ten_chung_tu' => 'Phiếu sửa chữa',
                'muc_dich_su_dung' => 'Phiếu sửa chữa' , 'to_chuc_id' => 1, 'created_at' => date("Y-m-d")
            ]
        ];

        DB::table('loai_chung_tus')->insert($listChungTus);
    }
}
