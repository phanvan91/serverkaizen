<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NhapXuatXac extends Model
{
    /**
     * @SWG\Definition(
     *   definition="NhapXuatXac",
     *   type="object",
     *   required={"ton_dau_ky"},
     *     @SWG\Property(property="ton_dau_ky", type="integer"),
     *     @SWG\Property(property="chung_tu_kho_tot_id", type="integer"),
     *     @SWG\Property(property="linh_kien_id", type="integer"),
     *     @SWG\Property(property="so_luong_yc", type="integer"),
     *     @SWG\Property(property="loai_giao_dich", type="integer"),
     *     @SWG\Property(property="so_luong_thuc", type="integer"),
     *     @SWG\Property(property="don_gia", type="float"),
     *     @SWG\Property(property="ton_cuoi_ky", type="integer"),
     *     @SWG\Property(property="kho_id", type="integer"),
     *     @SWG\Property(property="da_duyet", type="boolean"),
     *     @SWG\Property(property="da_huy", type="boolean"),
     * ),
     */

    protected $fillable = [
        'ton_dau_ky',
        'chung_tu_kho_tot_id',
        'linh_kien_id',
        'so_luong_yc',
        'loai_giao_dich',
        'so_luong_thuc',
        'don_gia',
        'ton_cuoi_ky',
        'kho_id',
        'da_duyet',
        'da_huy',
    ];

    public function nganhHang()
    {
        return $this->belongsTo(NganhHang::class);
    }
}
