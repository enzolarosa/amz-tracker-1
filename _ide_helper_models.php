<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AmzProduct
 *
 * @property int $id
 * @property string $asin
 * @property string|null $title
 * @property string|null $description
 * @property string|null $featureDescription
 * @property string|null $author
 * @property string|null $stars
 * @property string|null $review
 * @property \Illuminate\Support\Collection|null $images
 * @property string|null $currency
 * @property string|null $itemDetailUrl
 * @property \Illuminate\Support\Collection|null $sellers
 * @property string|null $start_price
 * @property string|null $previous_price
 * @property string|null $current_price
 * @property int $enabled
 * @property string|null $min_price
 * @property string|null $min_price_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AmzProductLog[] $logs
 * @property-read int|null $logs_count
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereAsin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereCurrentPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereFeatureDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereItemDetailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereMinPriceAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct wherePreviousPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereSellers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereStars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereStartPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProduct whereUpdatedAt($value)
 */
	class AmzProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AmzProductLog
 *
 * @property int $id
 * @property int $amz_product_id
 * @property \Illuminate\Support\Collection $history
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AmzProduct $product
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductLog whereAmzProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductLog whereHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductLog whereUpdatedAt($value)
 */
	class AmzProductLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AmzProductQueue
 *
 * @property int $id
 * @property int $amz_product_id
 * @property \Illuminate\Support\Carbon|null $reserved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AmzProduct $product
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductQueue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductQueue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductQueue query()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductQueue whereAmzProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductQueue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductQueue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductQueue whereReservedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductQueue whereUpdatedAt($value)
 */
	class AmzProductQueue extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AmzProductUser
 *
 * @property int $id
 * @property int $user_id
 * @property int $amz_product_id
 * @property int $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AmzProduct $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductUser whereAmzProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductUser whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AmzProductUser whereUserId($value)
 */
	class AmzProductUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property int $id
 * @property int $user_id
 * @property int $amz_product_id
 * @property bool $sent
 * @property string|null $previous_price
 * @property string|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AmzProduct $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereAmzProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification wherePreviousPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProxyServer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyServer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProxyServer query()
 */
	class ProxyServer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RequestLog
 *
 * @property int $id
 * @property string $provider
 * @property string $request_id
 * @property \Illuminate\Support\Collection $request
 * @property \Illuminate\Support\Collection $response
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestLog whereUpdatedAt($value)
 */
	class RequestLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SearchList
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SearchList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchList query()
 */
	class SearchList extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Setting
 *
 * @property string $id
 * @property string $key
 * @property string|null $value
 * @property string|null $expire_at
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereValue($value)
 */
	class Setting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ShortUrl
 *
 * @property int $id
 * @property string $code
 * @property string $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ShortUrl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShortUrl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShortUrl query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShortUrl whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortUrl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortUrl whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortUrl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortUrl whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortUrl whereUpdatedAt($value)
 */
	class ShortUrl extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $batch_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $username
 * @property string|null $language_code
 * @property string|null $email
 * @property string|null $tId
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property int $active
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AmzProduct[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\WishList
 *
 * @property int $id
 * @property int $user_id
 * @property string $url
 * @property int $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WishList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WishList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WishList query()
 * @method static \Illuminate\Database\Eloquent\Builder|WishList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WishList whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WishList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WishList whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WishList whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WishList whereUserId($value)
 */
	class WishList extends \Eloquent {}
}

