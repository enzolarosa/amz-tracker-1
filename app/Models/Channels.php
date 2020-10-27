<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Channels extends Model
{
    use HasFactory;
    use Notifiable;

    protected $casts = [
        //'configuration' => 'object',
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

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        return $this->configuration->route;
    }

    public function notification()
    {
        return $this->morphOne(Notification::class, 'notificable');
    }
}
