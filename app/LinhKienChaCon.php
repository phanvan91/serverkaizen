<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinhKienChaCon extends Model
{
    /**
     * @SWG\Definition(
     *   definition="LinhKienChaCon",
     *   type="object",
     *   required={"linh_kien_cha_id"},
     *     @SWG\Property(property="linh_kien_cha_id", type="integer"),
     *     @SWG\Property(property="linh_kien_con_id", type="integer"),
     * ),
     */

    protected $fillable = [
        'linh_kien_cha_id',
        'linh_kien_con_id'
    ];

    public function cha()
    {
        return $this->belongsTo(LinhKien::class,'linh_kien_cha_id');
    }

    public function con()
    {
        return $this->belongsTo(LinhKien::class,'linh_kien_con_id');
    }
}
