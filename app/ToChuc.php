<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToChuc extends Model
{
    /**
     * @SWG\Definition(
     *   definition="ToChuc",
     *   type="object",
     *   required={"ten"},
     *     @SWG\Property(property="ten", type="string", example="Thieu Lam Tu"),
     *     @SWG\Property(property="ngay_bd", type="date"),
     *     @SWG\Property(property="ngay_kt", type="date"),
     * ),
     */

    protected $fillable = [
        'ten',
        'ngay_bd',
        'ngay_kt'
    ];

    public function congTy() {
        return $this->hasMany(CongTy::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }

    public function trungTamBaoHanh() {
        return $this->hasMany(TrungTamBaoHanh::class);
    }

    public function tramBaoHanh() {
        return $this->hasMany(TramBaoHanh::class);
    }

    public function danhSachChiPhiDiLai() {
        return $this->hasMany(DanhSachChiPhiDiLai::class);
    }

    public function dachSachNguyenNhan() {
        return $this->hasMany(NguyenNhan::class);
    }

    public function danhSachTinhTrangHuHong() {
        return $this->hasMany(TinhTrangHuHong::class);
    }

    public function danhSachHuongKhacPhuc() {
        return $this->hasMany(HuongKhacPhuc::class);
    }

    public function danhSachBangTinhCongSuaChua() {
        return $this->hasMany(BangTinhCongSuaChua::class);
    }

    public function danhSachCauHoi() {
        return $this->hasMany(CauHoi::class);
    }

    public function danhSachHeThongTaiKhoangKeToan() {
        return $this->hasMany(HeThongTaiKhoangKeToan::class);
    }

    public function danhSachLoaiChungTu() {
        return $this->hasMany(LoaiChungTu::class);
    }

    public function danhSachLinhKien() {
        return $this->hasMany(LinhKien::class);
    }

    public function danhSachSoHieuChungTu() {
        return $this->hasMany(SoHieuChungTu::class);
    }

    public function danhSachKhachHang() {
        return $this->hasMany(KhachHang::class);
    }
    public function danhSachCauTraLoi() {
        return $this->hasMany(CauTraLoi::class);
    }

    public function danhSachLoaiNguoiDung() {
        return $this->hasMany(LoaiNguoiDung::class);
    }

    public function danhSachKho() {
        return $this->hasMany(Kho::class);
    }

    public function danhSachDoiTuongPhapNhan() {
        return $this->hasMany(DoiTuongPhapNhan::class);
    }

    public function danhSachTrungTamBaoHanh() {
        return $this->hasMany(DonDatHang::class);
    }

    public function danhSachDonDatHang() {
        return $this->hasMany(DonDatHang::class);
    }

    public function serials() {
        return $this->hasMany(Serial::class);
    }

    public function danhSachPhieuSuaChua() {
        return $this->hasMany(PhieuSuaChua::class);
    }
}
