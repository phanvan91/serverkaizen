<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class DatHangChiTiet extends Model
{
    /**
     * @SWG\Definition(
     *   definition="DatHangChiTiet",
     *   type="object",
     *   required={"linh_kien_id"},
     *     @SWG\Property(property="linh_kien_id", type="integer"),
     *     @SWG\Property(property="so_luong", type="integer"),
     * ),
     */

    protected $fillable = [
        'linh_kien_id',
        'so_luong'
    ];
}
