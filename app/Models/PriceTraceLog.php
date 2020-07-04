<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class PriceTraceLog extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'price_trace_logs';

    protected $fillable = [
        'price_trace_id',
        'price',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(PriceTrace::class, 'price_trace_log');
    }
}
