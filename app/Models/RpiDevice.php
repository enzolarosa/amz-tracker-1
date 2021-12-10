<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RpiDevice extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'external_id',
        'name',
        'description',
        'mac_address',
        'active',
        'vendor_id'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static function booted()
    {
        self::creating(function (RpiDevice $device) {
            $device->external_id ??= Str::uuid();
        });
    }
}
