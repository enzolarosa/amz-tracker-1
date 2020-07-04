<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /**
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(PriceTraceLog::class, 'price_trace_id');
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
