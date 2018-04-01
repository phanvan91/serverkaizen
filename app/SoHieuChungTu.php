<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoHieuChungTu extends Model
{

    use SoftDeletes;

    protected $fillable = [
      'so_hieu_chung_tu',
      'ten_chung_tu',
      'muc_dich_su_dung',
      'tai_khoang_no_id',
      'tai_khoang_co_id',
      'loai_chung_tu_id'
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function loaiChungTu() {
        return $this->belongsTo(LoaiChungTu::class);
    }

    public function tai_khoan_co() {
        return $this->belongsTo(HeThongTaiKhoangKeToan::class, 'tai_khoang_co_id');
    }

    public function tai_khoan_no() {
        return $this->belongsTo(HeThongTaiKhoangKeToan::class, 'tai_khoang_no_id');
    }
}
