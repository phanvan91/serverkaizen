<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NguyenNhan extends Model
{

    /**
     * @SWG\Definition(
     *   definition="NguyenNhan",
     *   type="object",
     *   required={"ma_nguyen_nhan", "mo_ta", "nganh_hang_id", "to_chuc_id"},
     *     @SWG\Property(property="ma_nguyen_nhan", type="string"),
     *     @SWG\Property(property="mo_ta", type="string"),
     *     @SWG\Property(property="nganh_hang_id", type="integer"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */

    protected $fillable = [
        'ma_nguyen_nhan',
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
}
