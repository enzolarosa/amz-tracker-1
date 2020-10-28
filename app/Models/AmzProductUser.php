<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmzProductUser extends Model
{
    protected $table = 'amz_product_user';

    protected $fillable = [
        'trackable_id',
        'trackable_type',
        'amz_product_id',
        'enabled'
    ];

    /* public function user()
     {
         return $this->belongsTo(User::class);
     }*/

    public function product()
    {
        return $this->belongsTo(AmzProduct::class, 'amz_product_id');
    }

    public function trackable()
    {
        return $this->morphTo();
    }
}
