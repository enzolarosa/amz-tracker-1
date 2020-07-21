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
 * App\Models\AmzProductLog
 *
 * @property int $id
 * @property int $amz_product_id
 * @property \Illuminate\Support\Collection $history
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AmzProduct $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductLog whereAmzProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductLog whereHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductLog whereUpdatedAt($value)
 */
	class AmzProductLog extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShortUrl whereUpdatedAt($value)
 */
	class ShortUrl extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUsername($value)
 */
	class User extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductUser whereAmzProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductUser whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProductUser whereUserId($value)
 */
	class AmzProductUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProxyServer
 *
 * @property int $id
 * @property string $proxy
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyServer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyServer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyServer whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyServer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyServer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyServer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyServer whereProxy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProxyServer whereUpdatedAt($value)
 */
	class ProxyServer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Notification
 *
 * @property int $id
 * @property int $user_id
 * @property int $amz_product_id
 * @property bool $sent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AmzProduct $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereAmzProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RequestLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RequestLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RequestLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RequestLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RequestLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RequestLog whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RequestLog whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RequestLog whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RequestLog whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RequestLog whereUpdatedAt($value)
 */
	class RequestLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Setting
 *
 * @property string $id
 * @property string $key
 * @property string|null $value
 * @property string|null $expire_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereExpireAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Setting whereValue($value)
 */
	class Setting extends \Eloquent {}
}

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
 * @property float|null $start_price
 * @property float|null $preview_price
 * @property float|null $current_price
 * @property int $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AmzProductLog[] $logs
 * @property-read int|null $logs_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereAsin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereCurrentPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereFeatureDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereItemDetailUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct wherePreviewPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereSellers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereStars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereStartPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AmzProduct whereUpdatedAt($value)
 */
	class AmzProduct extends \Eloquent {}
}

