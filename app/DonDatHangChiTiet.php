<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DonDatHangChiTiet extends Model
{
    protected $table = 'dat_hang_chi_tiets';

    protected $fillable = [
        'linh_kien_id',
        'so_luong'
    ];

    public function donDatHang() {
        return $this->belongsTo(DonDatHang::class, 'don_dat_hang_id');
    }

    public function linhKien() {
        return $this->belongsTo(LinhKien::class, 'linh_kien_id');
    }
}
