<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'amz_product_id',
        'sent',
        'price',
        'previous_price',
    ];

    protected $casts = [
        'price' => 'decimal:10,4',
        'sent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(AmzProduct::class);
    }
}
