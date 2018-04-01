<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NhomLinhKien extends Model
{
    /**
     * @SWG\Definition(
     *   definition="NhomLinhKien",
     *   type="object",
     *   required={"ten"},
     *     @SWG\Property(property="ma", type="string"),
     *     @SWG\Property(property="ten", type="string", example="Thieu Lam Tu"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */

    protected $fillable = [
        'ma',
        'ten',
        'to_chuc_id'
    ];

    public function toChuc()
    {
        return $this->belongsTo(SanPham::class);
    }
}
