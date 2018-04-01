<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinhKien extends Model
{
    /**
     * @SWG\Definition(
     *   definition="LinhKien",
     *   type="object",
     *   required={"ten"},
     *     @SWG\Property(property="ma", type="string"),
     *     @SWG\Property(property="ten", type="string", example="Thieu Lam Tu"),
     *     @SWG\Property(property="gia_ban", type="float"),
     *     @SWG\Property(property="don_vi", type="string"),
     *     @SWG\Property(property="thang_gia_han_sau_bao_hanh", type="integer"),
     *     @SWG\Property(property="linh_kien_ao", type="boolean"),
     *     @SWG\Property(property="linh_kien_ao", type="boolean"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     *     @SWG\Property(property="nhom_linh_kien_id", type="integer"),
     *     @SWG\Property(property="san_pham_id", type="integer"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */

    protected $fillable = [
        'ma',
        'ten',
        'to_chuc_id',
        'gia_ban',
        'don_vi',
        'thang_gia_han_sau_bao_hanh',
        'linh_kien_ao',
        'kich_hoat'
    ];

    public function nganhHang()
    {
        return $this->belongsTo(SanPham::class);
    }

    public function toChuc()
    {
        return $this->belongsTo(SanPham::class);
    }

    public function nhomLinhKien()
    {
        return $this->belongsTo(NhomLinhKien::class);
    }
}
