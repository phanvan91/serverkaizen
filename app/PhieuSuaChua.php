<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhieuSuaChua extends Model
{
    /**
     * @SWG\Definition(
     *   definition="PhieuSuaChua",
     *   type="object",
     *   required={"user_id", "khach_hang_id", "cau_tra_loi_id", "to_chuc_id"},
     *     @SWG\Property(property="kenh_tiep_nhan", type="string"),
     *     @SWG\Property(property="uu_tien", type="integer"),
     *     @SWG\Property(property="loai_hinh_dv", type="integer"),
     *     @SWG\Property(property="ngay_tiep_nhan", type="date"),
     *     @SWG\Property(property="noi_thuc_hien", type="integer"),
     *     @SWG\Property(property="ngay_hoan_tat_mong_muon", type="date"),
     *     @SWG\Property(property="thong_tin_dich_vu_ghi_chu", type="string"),
     *     @SWG\Property(property="hinh_anh", type="string"),
     *     @SWG\Property(property="user_id", type="integer"),
     *     @SWG\Property(property="khach_hang_id", type="integer"),
     *     @SWG\Property(property="cau_tra_loi_id", type="integer"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */
    protected $fillable = [
        'kenh_tiep_nhan',
        'uu_tien',
        'loai_hinh_dv',
        'ngay_tiep_nhan',
        'noi_thuc_hien',
        'ngay_hoan_tat_mong_muon',
        'thong_tin_dich_vu_ghi_chu',
        'hinh_anh',
        'user_id',
        'khach_hang_id',
        'serial_id',
        'cau_tra_loi_id',
        'to_chuc_id',
        'tram_bao_hanh_id',
        'ghi_chu'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function danhSachTinhTrangHuHong() {
        return $this->belongsToMany(TinhTrangHuHong::class, 'phieusuachua_tinhtranghuhong', 'phieu_sua_chua_id', 'tinh_trang_hu_hong_id');
    }

    public function danhSachChiPhiDiLai() {
        return $this
            ->belongsToMany(DanhSachChiPhiDiLai::class, 'dscpdl_psc', 'phieu_sua_chua_id', 'danh_sach_chi_phi_di_lai_id');
    }

    public function danhSachHuongKhacPhuc() {
        return $this
            ->belongsToMany(HuongKhacPhuc::class, 'huong_khac_phuc_phieu_sua_chua', 'phieu_sua_chua_id', 'huong_khac_phuc_id');
    }

    public function danhSachNguyenNhan() {
        return $this
            ->belongsToMany(NguyenNhan::class, 'nguyennhan_phieusuachua', 'phieu_sua_chua_id', 'nguyen_nhan_id');
    }

    public function notes() {
        return $this->hasMany(GhiChu::class);
    }

    public function danhSachBangTinhCongSuaChua() {
        return $this
            ->belongsToMany(BangTinhCongSuaChua::class, 'btcsc_psc', 'phieu_sua_chua_id', 'bang_tinh_cong_sua_chua_id');
    }
}
