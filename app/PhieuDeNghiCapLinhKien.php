<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhieuDeNghiCapLinhKien extends Model
{
    protected $fillable = [
        'thoi_han_can_vat_tu',
        'ly_do',
        'trang_thai',
        'phieu_sua_chua_id',
        'phieu_chuyen_kho_id',
        'phieu_xuat_kho_id',
        'nguoi_tao_id',
        'cong_ty_id',
        'kho_tram_id',
        'kho_trung_tam_id',
    ];

    public function congTy() {
        return $this->belongsTo(CongTy::class, 'cong_ty_id');
    }

    public function kho_tram(){
        return $this->belongsTo(Kho::class, 'kho_tram_id');
    }

    public function kho_trung_tam(){
        return $this->belongsTo(Kho::class, 'kho_trung_tam_id');
    }

    public function linh_kiens(){
        return $this->hasMany(DeNghiCapLinhKienChiTiet::class, 'phieu_de_nghi_id');
    }

    public function nguoi_tao(){
        return $this->hasOne(User::class, 'id', 'nguoi_tao_id');
    }
}
