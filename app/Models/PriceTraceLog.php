<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PriceTraceLog extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'price_trace_logs';

    protected $fillable = [
        'price_trace_id',
        'price',
    ];
}
