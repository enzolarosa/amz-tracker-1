<?php

namespace App\Jobs\Common;

use App\Jobs\Job;
use App\Models\QueriesLogger;
use Carbon\Carbon;
use DateTime;

class QueriesLoggerJob extends Job
{
    protected string $sql;
    protected array $bindings;
    protected float $time;

    public function handle()
    {
        $sql = $this->sql;
        foreach ($this->bindings as $val) {
            if ($val instanceof DateTime) {
                $val = Carbon::parse($val)->toIso8601String();
            }
            $sql = preg_replace('/\?/', "'{$val}'", $sql, 1);
        }

        QueriesLogger::query()->forceCreate([
            'query'    => $this->sql,
            'sql'      => $sql,
            'bindings' => json_encode($this->bindings),
            'timing'   => $this->time,
        ]);
    }

    public function setSql(string $sql): QueriesLoggerJob
    {
        $this->sql = $sql;
        return $this;
    }

    public function setBindings(array $bindings): QueriesLoggerJob
    {
        $this->bindings = $bindings;
        return $this;
    }

    public function setTime(float $time): QueriesLoggerJob
    {
        $this->time = $time;
        return $this;
    }
}
