<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class TonKhoTot extends Model
{
    /**
     * @SWG\Definition(
     *   definition="TonKhoTot",
     *   type="object",
     *   required={"linh_kien_id"},
     *     @SWG\Property(property="linh_kien_id", type="integer"),
     *     @SWG\Property(property="kho_id", type="integer"),
     *     @SWG\Property(property="ma", type="string"),
     *     @SWG\Property(property="ton_dau", type="integer"),
     *     @SWG\Property(property="nhap_kho", type="integer"),
     *     @SWG\Property(property="xuat_bh", type="integer"),
     *     @SWG\Property(property="xuat_ngoai_bh", type="integer"),
     *     @SWG\Property(property="xuat_noi_bo", type="integer"),
     *     @SWG\Property(property="so_luong_ton", type="integer"),
     *     @SWG\Property(property="ton_cuoi", type="integer"),


     * ),
     */

    protected $fillable = [
        'linh_kien_id',
        'kho_id',
        'ma',
        'ton_dau',
        'nhap_kho',
        'xuat_bh',
        'xuat_ngoai_bh',
        'so_luong_ton',
        'ton_cuoi'
    ];
}
