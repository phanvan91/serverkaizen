<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrungTamBaoHanh extends Model
{

    /**
     * @SWG\Definition(
     *   definition="TrungTamBaoHanh",
     *   type="object",
     *   required={"ten", "ma", "dia_chi", "to_chuc_id", "cong_ty_id"},
     *     @SWG\Property(property="ma", type="string"),
     *     @SWG\Property(property="ten", type="string"),
     *     @SWG\Property(property="dia_chi", type="string"),
     *     @SWG\Property(property="cong_ty_id", type="integer"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */
    protected $fillable = [
        'ma',
        'ten',
        'dia_chi',
        'cong_ty_id',
        'to_chuc_id'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function congTy() {
        return $this->belongsTo(CongTy::class);
    }

    public function tramBaoHanh() {
        return $this->hasMany(TramBaoHanh::class);
    }

    public function donDatHang() {
        return $this->hasMany(DonDatHang::class);
    }

    public function danhSachChiPhiDiLai() {
        return $this->hasMany(DanhSachChiPhiDiLai::class);
    }

    public function danhSachBangTinhCongSuaChua() {
        return $this->hasMany(BangTinhCongSuaChua::class);
    }

    public function kho() {
        return $this->hasMany(Kho::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }
}
