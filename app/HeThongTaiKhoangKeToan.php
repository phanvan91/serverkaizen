<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeThongTaiKhoangKeToan extends Model
{
    use SoftDeletes;

    /**
     * @SWG\Definition(
     *   definition="HeThongTaiKhoangKeToan",
     *   type="object",
     *   required={"so_hieu_tai_khoang", "ten_tai_khoang", "to_chuc_id"},
     *     @SWG\Property(property="so_hieu_tai_khoang", type="string"),
     *     @SWG\Property(property="ten_tai_khoang", type="string"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */
    protected $fillable = [
        'so_hieu_tai_khoang',
        'ten_tai_khoang',
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

}
