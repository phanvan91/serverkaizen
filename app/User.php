<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'dien_thoai', 'tram_bao_hanh_id', 'trung_tam_bao_hanh_id', 'loai_nguoi_dung_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function toChuc() {
        return $this->belongsTo(ToChuc::class);
    }

    public function loaiNguoiDung() {
        return $this->hasOne(LoaiNguoiDung::class);
    }

    public function trungTamBaoHanh() {
        return $this->belongsTo(TrungTamBaoHanh::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
