<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CauHoi extends Model
{
     use SoftDeletes;
    /**
     * @SWG\Definition(
     *   definition="CauHoi",
     *   type="object",
     *   required={"cau_hoi", "ten", "to_chuc_id"},
     *     @SWG\Property(property="cau_hoi", type="string"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'cau_hoi',
        'to_chuc_id'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }
}
