<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelLinhkien extends Model
{
    /**
     * @SWG\Definition(
     *   definition="ModelLinhkien",
     *   type="object",
     *   required={"linh_kien_id"},
     *     @SWG\Property(property="linh_kien_id", type="integer"),
     *     @SWG\Property(property="model_id", type="integer"),
     * ),
     */

    protected $fillable = [
        'model_id',
        'linh_kien_id'
    ];
}
