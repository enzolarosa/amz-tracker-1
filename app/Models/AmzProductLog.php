<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmzProductLog extends Model
{
    protected $fillable = [
        'amz_product_id',
        'history',
    ];
    protected $casts = [
        'history' => 'collection',
    ];

    public function product()
    {
        return $this->belongsTo(AmzProduct::class);
    }
}
