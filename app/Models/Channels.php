<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channels extends Model
{
    use HasFactory;

    protected $casts = [
        'configuration' => 'collection',
    ];

    protected $fillable = [
        'team_id',
        'name',
        'configuration',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
