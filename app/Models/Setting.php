<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'key',
        'value',
        'expire_at',
    ];

    /**
     * @param string $key
     * @return Builder|Model|self
     */
    public static function read(string $key)
    {
        return self::query()->firstOrCreate(['key' => $key]);
    }

    /**
     * @param string $key
     * @param null $value
     * @param Carbon|null $expire_at
     * @return Builder|Model|self
     */
    public static function store(string $key, $value = null, Carbon $expire_at = null)
    {
        return self::query()->updateOrCreate(['key' => $key], [
            'value' => $value,
            'expire_at' => $expire_at
        ]);
    }
}
