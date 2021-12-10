<?php

namespace App\Models;

use App\Events\Common\QueriesLoggerEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QueriesLogger extends Model
{
    use SoftDeletes, MassPrunable;

    protected $connection = 'logs';
    protected $table = 'queries_logger';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function log(string $sql, array $bindings, float $time): void
    {
        event(
            (new QueriesLoggerEvent())
                ->setBindings($bindings)
                ->setTime($time)
                ->setSql($sql)
        );
    }

    public function prunable(): Builder
    {
        return $this->where('created_at', '<=', now()->subWeek());
    }
}
