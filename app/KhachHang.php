<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    /**
     * @SWG\Definition(
     *   definition="KhachHang",
     *   type="object",
     *   required={"ten", "ma", "dien_thoai", "tinh_tp", "quan_huyen", "dia_chi", "to_chuc_id"},
     *     @SWG\Property(property="ten", type="string"),
     *     @SWG\Property(property="ma", type="string"),
     *     @SWG\Property(property="loai", type="string"),
     *     @SWG\Property(property="dien_thoai", type="string"),
     *     @SWG\Property(property="email", type="string"),
     *     @SWG\Property(property="tinh_tp", type="integer"),
     *     @SWG\Property(property="quan_huyen", type="integer"),
     *     @SWG\Property(property="dia_chi", type="string"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */
    protected $fillable = [
        'ten',
        'ma',
        'loai',
        'dien_thoai',
        'email',
        'tinh_tp',
        'quan_huyen',
        'dia_chi',
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function serials() {
        return $this->hasMany(Serial::class);
    }
}
