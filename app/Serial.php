<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    /**
     * @SWG\Definition(
     *   definition="Serial",
     *   type="object",
     *   required={"serial"},
     *     @SWG\Property(property="serial", type="string"),
     *     @SWG\Property(property="trang_thai", type="integer"),
     *     @SWG\Property(property="model_id", type="integer"),
     *     @SWG\Property(property="san_pham_id", type="integer"),
     *     @SWG\Property(property="nganh_hang_id", type="integer"),
     *     @SWG\Property(property="ngay_san_xuat", type="date"),
     *     @SWG\Property(property="ngay_xuat_kho", type="date"),
     *     @SWG\Property(property="ngay_kich_hoat_bh", type="date"),
     *     @SWG\Property(property="ngay_het_han", type="date"),
     * ),
     */

    protected $fillable = [
        'serial',
        'trang_thai',
        'model_id',
        'san_pham_id',
        'nganh_hang_id',
        'ngay_san_xuat',
        'ngay_xuat_kho',
        'ngay_kich_hoat_bh',
        'ngay_het_han',
        'khach_hang_id'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function nganhHang()
    {
        return $this->belongsTo(NganhHang::class);
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class);
    }

    public function model()
    {
        return $this->belongsTo(Models::class);
    }

    public function khachHang() {
        return $this->belongsTo(KhachHang::class);
    }
}
