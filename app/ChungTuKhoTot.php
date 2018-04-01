<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChungTuKhoTot extends Model
{

    protected $fillable = [
        'cong_ty_id',
        'phieu_sua_chua_id',
        'phieu_de_nghi_id',
        'tk_no_id',
        'tk_co_id',
        'doi_tuong_no_id',
        'doi_tuong_co_id',
        'ngay_ct',
        'so_ct',
        'ma_ct_id',
        'ngay_nhan',
        'don_dat_hang_id',
        'ten_nguoi_giao_nhan',
        'so_ct_goc',
        'ngay_ct_goc',
        'don_vi_ct_goc',
        'kho_nhap_id',
        'kho_xuat_id',
        'tong_so_tien',
        'trang_thai',
        'nguoi_tao_id',
        'tong_so_tien_truoc_thue',
        'phan_tram_thue',
        'so_luong_nhan'
    ];

    protected $dates = ['deleted_at'];

    public function congTy() {
        return $this->belongsTo(CongTy::class, 'cong_ty_id');
    }

    public function phieu_sua_chua(){
        return $this->belongsTo(PhieuSuaChua::class, 'phieu_sua_chua_id');
    }

    public function tk_no() {
        return $this->belongsTo(HeThongTaiKhoangKeToan::class, 'tk_no_id');
    }

    public function tk_co() {
        return $this->belongsTo(HeThongTaiKhoangKeToan::class, 'doi_tuong_co_id');
    }

    public function doi_tuong_no(){
        return $this->belongsTo(DoiTuongPhapNhan::class, 'doi_tuong_no_id');
    }

    public function doi_tuong_co(){
        return $this->belongsTo(DoiTuongPhapNhan::class, 'doi_tuong_no_id');
    }

    public function kho_nhap(){
        return $this->belongsTo(Kho::class, 'kho_nhap_id');
    }

    public function kho_xuat(){
        return $this->belongsTo(Kho::class, 'kho_xuat_id');
    }

    public function loai_chung_tu(){
        return $this->belongsTo(SoHieuChungTu::class, 'ma_ct_id');
    }

    public function don_dat_hang(){
        return $this->belongsTo(DonDatHang::class, 'don_dat_hang_id');
    }

    public function linh_kiens(){
        return $this->hasMany(NhapXuatTot::class, 'chung_tu_id');
    }

    public function nguoi_tao(){
        return $this->hasOne(User::class, 'id', 'nguoi_tao_id');
    }

    public function phieu_de_nghi(){
        return $this->belongsTo(PhieuDeNghiCapLinhKien::class, 'phieu_de_nghi_id');
    }
}
