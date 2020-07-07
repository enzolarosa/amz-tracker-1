<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AmzProductUser extends Pivot
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(AmzProduct::class);
    }
}
