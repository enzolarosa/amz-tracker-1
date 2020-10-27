<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShortUrl extends Model
{
    protected $fillable = [
        'code',
        'link',
    ];

    /**
     *
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'code';
    }

    /**
     * @param string $link
     * @return string
     */
    public static function hideLink(string $link)
    {
        $short = self::query()->create([
            'code' => Str::random(6),
            'link' => $link
        ]);

        return route('short-url-go', $short->code);
    }
}
