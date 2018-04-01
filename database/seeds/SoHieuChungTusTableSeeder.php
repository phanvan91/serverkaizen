<?php

use Illuminate\Database\Seeder;

class SoHieuChungTusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $soHieuChungTus = [
            [
                'so_hieu_chung_tu' => '01',
                'ten_chung_tu' => 'Phiếu nhập kho Việt Nhật',
                'muc_dich_su_dung' => 'Nhập kho LK từ Việt Nhật về kho trung tâm',
                'tai_khoang_no_id' => '2',
                'tai_khoang_co_id' => '5',
                'loai_chung_tu_id' => '1',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '02',
                'ten_chung_tu' => 'Phiếu nhập kho mua ngoài',
                'muc_dich_su_dung' => 'Nhập kho LK do nhân viên công ty mua ngoài nhập về kho trung tâm',
                'tai_khoang_no_id' => '2',
                'tai_khoang_co_id' => '1',
                'loai_chung_tu_id' => '1',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '03',
                'ten_chung_tu' => 'Phiếu nhập kho hàng nhập khẩu',
                'muc_dich_su_dung' => 'Nhập kho LK nhập khẩu',
                'tai_khoang_no_id' => '2',
                'tai_khoang_co_id' => '5',
                'loai_chung_tu_id' => '1',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '04',
                'ten_chung_tu' => 'Phiếu nhập kho LK tháo máy',
                'muc_dich_su_dung' => 'Nhập kho linh kiện do tháo rời từ thành phẩm',
                'tai_khoang_no_id' => '2',
                'tai_khoang_co_id' => '6',
                'loai_chung_tu_id' => '1',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '05',
                'ten_chung_tu' => 'Phiếu nhập kho LK khác',
                'muc_dich_su_dung' => 'Nhập kho linh kiện khi không thuộc các trường hợp trên',
                'tai_khoang_no_id' => '2',
                'tai_khoang_co_id' => null,
                'loai_chung_tu_id' => '1',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '01',
                'ten_chung_tu' => 'Xuất bán ',
                'muc_dich_su_dung' => 'Xuất bán khách lẻ, trạm bảo hành, nhà phân phối',
                'tai_khoang_no_id' => '7',
                'tai_khoang_co_id' => '2',
                'loai_chung_tu_id' => '2',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '02',
                'ten_chung_tu' => 'Xuất bán sửa chữa lưu động',
                'muc_dich_su_dung' => 'Xuất LK cho nhân viên BH lưu độ',
                'tai_khoang_no_id' => '4',
                'tai_khoang_co_id' => '2',
                'loai_chung_tu_id' => '2',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '03',
                'ten_chung_tu' => 'Xuất trả hàng ',
                'muc_dich_su_dung' => 'Xuất kho trả hàng nhận về bảo hành',
                'tai_khoang_no_id' => '5',
                'tai_khoang_co_id' => '3',
                'loai_chung_tu_id' => '2',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '04',
                'ten_chung_tu' => 'Xuất trường hợp khác',
                'muc_dich_su_dung' => 'Xuất kho trong trường hợp khác',
                'tai_khoang_no_id' => null,
                'tai_khoang_co_id' => null,
                'loai_chung_tu_id' => '2',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '01',
                'ten_chung_tu' => 'Chuyển kho LK cho trạm BH và PBH',
                'muc_dich_su_dung' => 'Chuyển LK cho Trạm BH',
                'tai_khoang_no_id' => '2',
                'tai_khoang_co_id' => '2',
                'loai_chung_tu_id' => '3',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '05',
                'ten_chung_tu' => 'Phiếu nhập kho LK khác',
                'muc_dich_su_dung' => 'Nhập kho linh kiện hỏng',
                'tai_khoang_no_id' => '2',
                'tai_khoang_co_id' => null,
                'loai_chung_tu_id' => '4',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '01',
                'ten_chung_tu' => 'Xuất bán LK',
                'muc_dich_su_dung' => 'Xuất bán linh kiện hỏng',
                'tai_khoang_no_id' => '7',
                'tai_khoang_co_id' => '2',
                'loai_chung_tu_id' => '5',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '01',
                'ten_chung_tu' => 'Chuyển kho LK cho trạm BH và PBH',
                'muc_dich_su_dung' => 'Chuyển LK từ Trạm/ Phòng BH về kho hỏng',
                'tai_khoang_no_id' => '2',
                'tai_khoang_co_id' => '2',
                'loai_chung_tu_id' => '6',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '05',
                'ten_chung_tu' => 'Phiếu nhập kho thành phẩm',
                'muc_dich_su_dung' => 'Nhập kho thành phẩm gửi bảo hành',
                'tai_khoang_no_id' => '3',
                'tai_khoang_co_id' => '5',
                'loai_chung_tu_id' => '7',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
            [
                'so_hieu_chung_tu' => '01',
                'ten_chung_tu' => 'Chuyển kho máy BH từ trạm A sang trạm B',
                'muc_dich_su_dung' => 'Chuyển máy từ Trạm A sang Trạm B Chuyển máy BH từ trạm về Phòng BH Chuyển máy BH từ trạm về Phòng BH',
                'tai_khoang_no_id' => '3',
                'tai_khoang_co_id' => '5',
                'loai_chung_tu_id' => '9',
                'to_chuc_id' => '1',
                'created_at' => date("Y-m-d")
            ],
        ];

        DB::table('so_hieu_chung_tus')->insert($soHieuChungTus);
    }
}
