<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThongTinDichVuNote extends Model
{
    protected $fillable = [
        'note',
        'user_id',
        'phieu_sua_chua_id'
    ];

    public function phieuSuaChua() {
        return $this->belongsTo(PhieuSuaChua::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
