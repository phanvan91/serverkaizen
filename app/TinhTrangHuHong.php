<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TinhTrangHuHong extends Model
{
    /**
     * @SWG\Definition(
     *   definition="TinhTrangHuHong",
     *   type="object",
     *   required={"ma_tinh_trang_hu_hong", "mo_ta", "nganh_hang_id", "to_chuc_id"},
     *     @SWG\Property(property="ma_tinh_trang_hu_hong", type="string"),
     *     @SWG\Property(property="mo_ta", type="string"),
     *     @SWG\Property(property="nganh_hang_id", type="integer"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */

    protected $fillable = [
        'ma_tinh_trang_hu_hong',
        'mo_ta',
        'nganh_hang_id',
        'to_chuc_id'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function nghanhHang() {
        return $this->belongsTo(NganhHang::class);
    }

    public function danhSachPhieuSuaChua() {
        return $this->belongsToMany(PhieuSuaChua::class);
    }
}
