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
 * App\Models\PriceTrace
 *
 * @property int $id
 * @property string $product_id
 * @property string $store
 * @property float $first_price
 * @property float $latest_price
 * @property float $current_price
 * @property int $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereCurrentPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereFirstPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereLatestPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTrace whereUpdatedAt($value)
 */
    class PriceTrace extends \Eloquent
    {
    }
}
