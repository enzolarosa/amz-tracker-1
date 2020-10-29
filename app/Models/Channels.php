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
        //    'configuration' => 'collection',
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

    public function notification()
    {
        return $this->morphOne(Notification::class, 'notificable');
    }

    public function searchList()
    {
        return $this->morphOne(SearchList::class, 'trackable');
    }

    public function wishList()
    {
        return $this->morphOne(WishList::class, 'trackable');
    }

    public function products()
    {
        return $this->morphOne(AmzProductUser::class, 'trackable');
        /*return $this->morphToMany(AmzProduct::class, 'trackable')->using(AmzProductUser::class)->withPivot([
                 'enabled',
             ])->wherePivot('enabled', $enabled);*/
    }

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        $config = json_decode($this->configuration);
        return $config->destination;
    }
}
