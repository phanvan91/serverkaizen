<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    /**
     * @SWG\Definition(
     *   definition="Models",
     *   type="object",
     *   required={"ten"},
     *     @SWG\Property(property="ten", type="string", example="Thieu Lam Tu"),
     *     @SWG\Property(property="kich_hoat", type="bolean"),
     *     @SWG\Property(property="ma", type="string"),
     * ),
     */

    protected $fillable = [
        'ten',
        'ma',
        'kich_hoat'
    ];

    public function sanPham() {
        return $this->belongsTo(SanPham::class);
    }

    public function nganhHang() {
        return $this->belongsTo(SanPham::class);
    }
}
