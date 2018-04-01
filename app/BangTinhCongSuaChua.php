<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BangTinhCongSuaChua extends Model
{
    use SoftDeletes;
    /**
     * @SWG\Definition(
     *   definition="BangTinhCongSuaChua",
     *   type="object",
     *   required={"ma_bang_tinh_cong_sua_chua", "ten", "don_gia", "trung_tam_bao_hanh_id", "to_chuc_id", "phieu_sua_chua_id"},
     *     @SWG\Property(property="ma_bang_tinh_cong_sua_chua", type="string"),
     *     @SWG\Property(property="ten", type="string"),
     *     @SWG\Property(property="don_gia", type="float"),
     *     @SWG\Property(property="trung_tam_bao_hanh_id", type="integer"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     *     @SWG\Property(property="phieu_sua_chua_id", type="integer"),
     * ),
     */

    protected $fillable = [

        'ma_huong_khac_phuc',
        'ten_huong_khac_phuc',
        'don_gia',
        'trung_tam_bao_hanh_id',
        'to_chuc_id'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function trungTamBaoHanh() {
        return $this->belongsTo(TrungTamBaoHanh::class);
    }
    
}
