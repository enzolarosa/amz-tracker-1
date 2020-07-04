<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\User
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 */
    class User extends \Eloquent implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\Access\Authorizable
    {
    }
}

namespace App\Models{
/**
 * App\Models\PriceTraceLog
 *
 * @property int $id
 * @property int $price_trace_id
 * @property float|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog wherePriceTraceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog whereUpdatedAt($value)
 */
    class PriceTraceLog extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable
    {
    }
}

namespace App\Models{
/**
 * App\Models\PriceTrace
 *
 * @property int $id
 * @property string|null $name
 * @property string $product_id
 * @property string $store
 * @property float|null $first_price
 * @property float|null $latest_price
 * @property float|null $current_price
 * @property int $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereCurrentPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereFirstPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereLatestPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereUpdatedAt($value)
 */
    class PriceTrace extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable
    {
    }
}
