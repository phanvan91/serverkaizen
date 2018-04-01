<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhapXuatTot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ton_dau_ky',
        'chung_tu_kho_tot_id',
        'linh_kien_id',
        'so_luong_yc',
        'loai_giao_dich',
        'so_luong_thuc',
        'don_gia',
        'ton_cuoi_ky',
        'kho_id',
        'da_duyet',
        'da_huy',
    ];

    protected $dates = ['deleted_at'];


    public function nganhHang()
    {
        return $this->belongsTo(NganhHang::class);
    }

    public function linh_kien(){
        return $this->belongsTo(LinhKien::class, 'linh_kien_id');
    }
}
