<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    /**
     * @SWG\Definition(
     *   definition="SanPham",
     *   type="object",
     *   required={"ten"},
     *     @SWG\Property(property="ten", type="string", example="Thieu Lam Tu"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     *     @SWG\Property(property="nganh_hang_id", type="integer"),
     *     @SWG\Property(property="kich_hoat", type="bolean"),
     *     @SWG\Property(property="ma", type="string"),
     * ),
     */

    protected $fillable = [
        'ten',
        'to_chuc_id',
        'ma',
        'kich_hoat'
    ];

    public function nganhHang()
    {
        return $this->belongsTo(NganhHang::class);
    }
}