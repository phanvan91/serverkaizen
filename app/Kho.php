<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Kho extends Model
{
    /**
     * @SWG\Definition(
     *   definition="Kho",
     *   type="object",
     *   required={"ten_kho"},
     *     @SWG\Property(property="ten_kho", type="string"),
     *     @SWG\Property(property="ma_kho", type="string"),
     *     @SWG\Property(property="loai_kho", type="integer"),
     *     @SWG\Property(property="tram_bao_hanh_id", type="integer"),
     *     @SWG\Property(property="trung_tam_bao_hanh_id", type="integer"),
     *     @SWG\Property(property="cong_ty_id", type="integer"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */

    protected $fillable = [
        'ten_kho',
        'ma_kho',
        'loai_kho',
        'tram_bao_hanh_id',
        'trung_tam_bao_hanh_id',
        'cong_ty_id',
        'to_chuc_id',
    ];
    public function tonKhoTot() {
        return $this->hasMany(TonKhoTot::class);
    }
    public function tonKhoXac() {
        return $this->hasMany(TonKhoXac::class);
    }
}
