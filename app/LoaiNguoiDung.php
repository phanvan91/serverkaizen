<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoaiNguoiDung extends Model
{
    use SoftDeletes;

    protected $fillable = ['ten_loai', 'dien_giai'];

    public function danhSachUser() {
        return $this->hasMany(User::class);
    }

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }
}
