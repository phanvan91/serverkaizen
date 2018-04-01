<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    /**
     * @SWG\Definition(
     *   definition="Request",
     *   type="object",
     *   required={"nguoi_gui_id", "ben_nhan_id", "ben_nhan_la_nhom", "da_xem","da_xu_ly","ghi_chu"},
     *     @SWG\Property(property="nguoi_gui_id", type="integer"),
     *     @SWG\Property(property="ben_nhan_id", type="integer"),
     *     @SWG\Property(property="ben_nhan_la_nhom", type="boolean"),
     *     @SWG\Property(property="da_xem", type="boolean"),
     *     @SWG\Property(property="ghi_chu", type="string"),
     * ),
     */
    protected $fillable = [
        'nguoi_gui_id',
        'ben_nhan_id',
        'ben_nhan_la_nhom',
        'da_xem',
        'da_xu_ly',
        'ghi_chu'
    ];
}
