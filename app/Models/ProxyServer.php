<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class ProxyServer extends Model
{
    protected $fillable = [
        'proxy',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @return ProxyServer|\Illuminate\Database\Eloquent\Builder|Model|Builder|object|null|self|null
     */
    public static function giveOne()
    {
        $proxy = self::query()
            ->where('updated_at', '<=', now()->subMinutes(2))
            ->where('active', true)
            ->inRandomOrder()
            ->take(1)
            ->first();
        if (is_null($proxy)){
            return null;
        }
        $proxy->touch();
        return $proxy;
    }
}
