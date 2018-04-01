<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class TonKhoXac extends Model
{
    /**
     * @SWG\Definition(
     *   definition="TonKhoXac",
     *   type="object",
     *   required={"linh_kien_id"},
     *     @SWG\Property(property="linh_kien_id", type="integer"),
     *     @SWG\Property(property="kho_id", type="integer"),
     *     @SWG\Property(property="ma", type="string"),
     *     @SWG\Property(property="ton_dau", type="integer"),
     *     @SWG\Property(property="nhap_kho", type="integer"),
     *     @SWG\Property(property="xuat_ban", type="integer"),
     *     @SWG\Property(property="ton_cuoi", type="integer"),
     * ),
     */
    protected $table = 'ton_kho_xaus';
    protected $fillable = [
        'linh_kien_id',
        'kho_id',
        'ma',
        'ton_dau',
        'nhap_kho',
        'xuat_ban',
        'ton_cuoi'
    ];
}
