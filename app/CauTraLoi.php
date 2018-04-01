<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CauTraLoi extends Model
{

    /**
     * @SWG\Definition(
     *   definition="CauTraLoi",
     *   type="object",
     *   required={"cau_tra_loi", "to_chuc_id"},
     *     @SWG\Property(property="cau_tra_loi", type="string"),
     *     @SWG\Property(property="da_thuc_hien", type="integer", description="Default 0 (chua thuc hien)"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */

    protected $fillable = [
        'cau_tra_loi',
        'da_thuc_hien',
        'to_chuc_id'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

}
