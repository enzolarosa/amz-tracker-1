<?php

namespace App\Events\Common;

class QueriesLoggerEvent
{
    protected string $sql;
    protected array $bindings;
    protected float $time;

    public function setSql(string $sql): QueriesLoggerEvent
    {
        $this->sql = $sql;
        return $this;
    }

    public function setBindings(array $bindings): QueriesLoggerEvent
    {
        $this->bindings = $bindings;
        return $this;
    }

    public function setTime(float $time): QueriesLoggerEvent
    {
        $this->time = $time;
        return $this;
    }

    public function getSql(): string
    {
        return $this->sql;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

    public function getTime(): float
    {
        return $this->time;
    }
}
