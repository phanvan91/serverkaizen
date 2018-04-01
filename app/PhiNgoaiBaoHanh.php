<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhiNgoaiBaoHanh extends Model
{

    /**
     * @SWG\Definition(
     *   definition="PhiNgoaiBaoHanh",
     *   type="object",
     *   required={"to_chuc_id", "so_luong", "phieu_sua_chua_id", "linh_kien_id"},
     *     @SWG\Property(property="so_luong", type="float"),
     *     @SWG\Property(property="linh_kien_id", type="integer"),
     *     @SWG\Property(property="phieu_sua_chua_id", type="integer"),
     *     @SWG\Property(property="phieu_sua_chua_id", type="integer"),
     *     @SWG\Property(property="to_chuc_id", type="date"),
     * ),
     */
    protected $fillable = [
        'so_luong',
        'linh_kien_id',
        'phieu_sua_chua_id',
        'to_chuc_id'
    ];
}
