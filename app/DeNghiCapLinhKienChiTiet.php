<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeNghiCapLinhKienChiTiet extends Model
{
    protected $fillable = [
        'phieu_de_nghi_id',
        'linh_kien_id',
        'so_luong',
        'don_gia',
    ];

    public function linh_kien(){
        return $this->belongsTo(LinhKien::class, 'linh_kien_id');
    }
}
