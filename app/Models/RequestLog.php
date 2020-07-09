<?php

namespace App\Models;

use App\Events\Common\WriteLogEvent;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $fillable = [
        'request_id',
        'request',
        'response',
        'provider',
        'timing'
    ];
    
    protected $casts = [
        'response' => 'collection',
        'request' => 'collection',
    ];

    public static function log(array $attributes): void
    {
        event(new WriteLogEvent($attributes));
    }
}
