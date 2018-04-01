<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TraLinhKienChiTiet extends Model
{
    /**
     * @SWG\Definition(
     *   definition="PhieuTraLinhKien",
     *   type="object",
     *   required={"ma_linh_kien_id"},
     *     @SWG\Property(property="ma_linh_kien_id", type="integer"),
     *     @SWG\Property(property="phieu_tra_linh_kien_id", type="integer"),
     *     @SWG\Property(property="so_luong", type="integer"),
     *     @SWG\Property(property="so_luong_thuc_nhan", type="integer"),
     * ),
     */

    protected $fillable = [
        'ma_linh_kien_id',
        'phieu_tra_linh_kien_id',
        'so_luong',
        'so_luong_thuc_nhan'
    ];
}
