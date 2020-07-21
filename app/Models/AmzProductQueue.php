<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmzProductQueue extends Model
{
    protected $fillable = [
        'amz_product_id',
        'reserved_at',
    ];

    protected $casts = [
        'reserved_at' => 'datetime'
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(AmzProduct::class, 'amz_product_id');
    }
}
