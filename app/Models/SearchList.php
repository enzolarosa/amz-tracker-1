<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchList extends Model
{
    use HasFactory;

    protected $fillable = [
        'trackable_type',
        'trackable_id',
        'keywords',
    ];

    public function trackable()
    {
        return $this->morphTo();
    }
}
