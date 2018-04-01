<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhieuSuaChuaLinhKien extends Model
{
    protected $fillable = [
        'gia_ban',
        'so_luong_cap',
        'so_luong_tra'
    ];

    public function phieuSuaChua() {
        return $this->belongsTo(PhieuSuaChua::class);
    }

    public function linhKien() {
        return $this->belongsTo(LinhKien::class);
    }
}
