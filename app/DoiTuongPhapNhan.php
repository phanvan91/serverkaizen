<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoiTuongPhapNhan extends Model
{
//    use SoftDeletes;

    /**
     * @SWG\Definition(
     *   definition="DoiTuongPhapNhan",
     *   type="object",
     *   required={"ten"},
     *     @SWG\Property(property="ten", type="string", example="Thieu Lam Tu"),
     *     @SWG\Property(property="ma", type="string"),
     * ),
     */

    protected $fillable = [
        'ten',
        'ma',
        'goc_id',
        'loai',
        'to_chuc_id'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }
}
