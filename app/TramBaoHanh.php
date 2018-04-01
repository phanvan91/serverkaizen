<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramBaoHanh extends Model
{
    /**
     * @SWG\Definition(
     *   definition="TramBaoHanh",
     *   type="object",
     *   required={"ten", "ma", "dia_chi", "to_chuc_id", "cong_ty_id", "trung_tam_bao_hanh_id"},
     *     @SWG\Property(property="ma", type="string"),
     *     @SWG\Property(property="ten", type="string"),
     *     @SWG\Property(property="dia_chi", type="string"),
     *     @SWG\Property(property="cong_ty_id", type="integer"),
     *     @SWG\Property(property="loai_tram", type="integer"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     *     @SWG\Property(property="trung_tam_bao_hanh_id", type="integer"),
     * ),
     */
    protected $fillable = [
        'ma',
        'ten',
        'dia_chi',
        'cong_ty_id',
        'trung_tam_bao_hanh_id',
        'to_chuc_id',
        'loai_tram',
        'don_vi_van_chuyen',
        'nguoi_dai_dien'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function congTy() {
        return $this->belongsTo(CongTy::class);
    }

    public function trungTamBaoHanh() {
        return $this->belongsTo(TrungTamBaoHanh::class);
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
}
