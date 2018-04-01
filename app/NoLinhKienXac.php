<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoLinhKienXac extends Model
{
    /**
     * @SWG\Definition(
     *   definition="NoLinhKienXac",
     *   type="object",
     *   required={"nhan_vien_id"},
     *     @SWG\Property(property="nhan_vien_id", type="integer"),
     *     @SWG\Property(property="tram_bao_hanh_id", type="integer"),
     *     @SWG\Property(property="linh_kien_id", type="integer"),
     *     @SWG\Property(property="so_luong_tot_cap", type="integer"),
     *     @SWG\Property(property="so_luong_xac_thu", type="integer"),
     * ),
     */

    protected $fillable = [
        'nhan_vien_id',
        'tram_bao_hanh_id',
        'linh_kien_id',
        'so_luong_tot_cap',
        'so_luong_xac_thu'
    ];

    public function user() {
        return $this->belongsTo(User::class,'nhan_vien_id','id');
    }

    public function tramBaoHanh() {
        return $this->belongsTo(TramBaoHanh::class);
    }

    public function linhKienCap() {
        return $this->belongsTo(LinhKien::class,'linh_kien_cap_id');
    }

    public function linhKienThuHoi() {
        return $this->belongsTo(LinhKien::class,'linh_kien_thu_hoi_id');
    }
}
