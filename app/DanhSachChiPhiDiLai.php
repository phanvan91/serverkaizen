<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanhSachChiPhiDiLai extends Model
{

    use SoftDeletes;

    /**
     * @SWG\Definition(
     *   definition="DanhSachChiPhiDiLai",
     *   type="object",
     *   required={"tinh", "thanh_pho", "quan", "km_mot_chieu", "km_khu_hoi", "don_gia", "thanh_tien_mot", "thanh_tien_hai",
     *     "tram_bao_hanh_id", "to_chuc_id", "cong_ty_id", "trung_tam_bao_hanh_id"},
     *     @SWG\Property(property="tinh", type="string"),
     *     @SWG\Property(property="thanh_pho", type="string"),
     *     @SWG\Property(property="quan", type="string"),
     *     @SWG\Property(property="km_mot_chieu", type="float"),
     *     @SWG\Property(property="km_khu_hoi", type="float"),
     *     @SWG\Property(property="don_gia", type="float"),
     *     @SWG\Property(property="thanh_tien_mot", type="float"),
     *     @SWG\Property(property="thanh_tien_hai", type="float"),
     *     @SWG\Property(property="tram_bao_hanh_id", type="integer"),
     *     @SWG\Property(property="to_chuc_id", type="integer"),
     *     @SWG\Property(property="trung_tam_bao_hanh_id", type="integer"),
     * ),
     */
    protected $fillable = [
        'quan',
        'thanh_pho',
        'phuong',
        'km_mot_chieu',
        'km_khu_hoi',
        'don_gia',
        'thanh_tien_mot',
        'thanh_tien_hai',
        'tram_bao_hanh_id',
        'to_chuc_id',
        'cong_ty_id',
        'trung_tam_bao_hanh_id',
        'ten_thanh_pho',
        'ten_quan',
        'ten_phuong'
    ];

    public function toChuc()
    {
        return $this->belongsTo(ToChuc::class);
    }

    public function congTy()
    {
        return $this->belongsTo(CongTy::class);
    }

    public function trungTamBaoHanh()
    {
        return $this->belongsTo(TrungTamBaoHanh::class);
    }

    public function tramBaoHanh()
    {
        return $this->belongsTo(TramBaoHanh::class);
    }

    public function danhSachPhieuSuaChua() {
        return $this->belongsToMany(PhieuSuaChua::class, 'dscpdl_psc', 'danh_sach_chi_phi_di_lai_id', 'phieu_sua_chua_id');
    }
}
