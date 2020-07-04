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
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $tId
 * @property int $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\Access\Authorizable {}
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
 * @property-read \App\Models\PriceTrace $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog wherePriceTraceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PriceTraceLog whereUpdatedAt($value)
 */
	class PriceTraceLog extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $tId
 * @property int $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PriceTrace[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PriceTraceLog[] $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
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
	class PriceTrace extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

