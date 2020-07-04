<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PriceTrace extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'price_trace';

    protected $fillable = [
        'product_id',
        'store',
        'first_price',
        'latest_price',
        'current_price',
        'enabled',
    ];
}
