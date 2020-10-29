<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use HasFactory;

    protected $fillable = [
        'trackable_type',
        'trackable_id',
        'url',
    ];

    public function trackable()
    {
        return $this->morphTo();
    }

}
