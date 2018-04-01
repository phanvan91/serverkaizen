<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CongTy extends Model
{
    use SoftDeletes;

    /**
     * @SWG\Definition(
     *   definition="CongTy",
     *   type="object",
     *   required={"to_chuc_id", "ma", "ten", "dia_chi", "ma_so_thue", "dien_thoai", "email"},
     *     @SWG\Property(property="ma", type="string", example="CTHN1"),
     *     @SWG\Property(property="ten", type="string", example="Cong ty Ha Noi 1"),
     *     @SWG\Property(property="dia_chi", type="string", example="66 Vo Van Tan"),
     *     @SWG\Property(property="ma_so_thue", type="string", example="ABCXYZ"),
     *     @SWG\Property(property="dien_thoai", type="string", example="09000 biet"),
     *     @SWG\Property(property="email", type="string", example="joe-doe@gmail.com"),
     *     @SWG\Property(property="web", type="string", example="*.*"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */

    protected $fillable = [
        'ma',
        'ten',
        'dia_chi',
        'ma_so_thue',
        'dien_thoai',
        'email',
        'web'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
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

    public function chungTuKhoTot() {
        return $this->hasMany(ChungTuKhoTot::class);
    }
}
