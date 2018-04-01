<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoaiChungTu extends Model
{
    use SoftDeletes;

    /**
     * @SWG\Definition(
     *   definition="LoaiChungTu",
     *   type="object",
     *   required={"loai_chung_tu", "ten_chung_tu", "muc_dich_su_dung", "to_chuc_id"},
     *     @SWG\Property(property="loai_chung_tu", type="string"),
     *     @SWG\Property(property="ten_chung_tu", type="string"),
     *     @SWG\Property(property="muc_dich_su_dung", type="string"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     * ),
     */
    protected $fillable = [
        'loai_chung_tu',
        'ten_chung_tu',
        'muc_dich_su_dung',
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function danhSachSoHieuChungTu() {
        return $this->hasMany(SoHieuChungTu::class);
    }
}
