<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GhiChu extends Model
{
    protected $fillable = [
        'note',
        'user_id',
        'phieu_sua_chua_id'
    ];
}
