<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class DonDatHang extends Model
{
    /**
     * @SWG\Definition(
     *   definition="DonDatHang",
     *   type="object",
     *   required={"ngay_nhan_hang"},
     *     @SWG\Property(property="ngay_nhan_hang", type="date"),
     *     @SWG\Property(property="so_ct", type="string"),
     *     @SWG\Property(property="nguoi_dat_id", type="string"),
     *     @SWG\Property(property="ly_do", type="integer"),
     * ),
     */

    protected $fillable = [
        'ngay_dat_hang',
        'ngay_nhan_hang',
        'so_ct',
        'nguoi_dat_id',
        'ly_do',
        'trung_tam_bao_hanh_id'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'nguoi_dat_id');
    }

    public function trungTamBaoHanh() {
        return $this->belongsTo(TrungTamBaoHanh::class);
    }

    public function danhSachDonDatHangChiTiet() {
        return $this->hasMany(DonDatHangChiTiet::class, 'don_dat_hang_id');
    }
}
