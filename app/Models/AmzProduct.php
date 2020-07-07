<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AmzProduct extends Model
{
    protected $fillable = [
        'asin',
        'title',
        'description',
        'featureDescription',
        'author',
        'stars',
        'review',
        'images',
        'currency',
        'itemDetailUrl',
        'sellers',
        'start_price',
        'preview_price',
        'current_price'
    ];

    protected $casts = [
        'sellers' => 'collection',
        'images' => 'collection',
    ];

    public function logs()
    {
        return $this->hasMany(AmzProductLog::class);
    }

    public function users($enabled = true)
    {
        return $this->belongsToMany(User::class)->using(AmzProductUser::class)->withPivot([
            'enabled',
        ])->wherePivot('enabled', $enabled);
    }
}