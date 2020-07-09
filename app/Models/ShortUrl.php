<?php

namespace App\Models;

use Hash;
use Illuminate\Database\Eloquent\Model;

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
            'code' => Hash::make($link),
            'link' => $link
        ]);

        return route('short-url-go', $short->code);
    }
}
