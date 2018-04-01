<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PhieuTraLinhKien extends Model
{
    /**
     * @SWG\Definition(
     *   definition="PhieuTraLinhKien",
     *   type="object",
     *   required={"loai_phieu"},
     *     @SWG\Property(property="linh_kien_id", type="integer"),
     *     @SWG\Property(property="loai_phieu", type="integer"),
     *     @SWG\Property(property="phieu_sua_chua_id", type="integer"),
     *     @SWG\Property(property="phieu_nhap_kho_id", type="integer"),
     *     @SWG\Property(property="tram_bao_hanh_id", type="integer"),
     * ),
     */

    protected $fillable = [
        'linh_kien_id',
        'loai_phieu',
        'phieu_sua_chua_id',
        'phieu_nhap_kho_id',
        'tram_bao_hanh_id'
    ];
}
