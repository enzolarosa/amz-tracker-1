<?php

namespace App\Jobs\Common;

use App\Jobs\Job;
use App\Models\RequestLog;

class WriteLogJob extends Job
{
    protected array $attributes;

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return WriteLogJob
     */
    public function setAttributes(array $attributes): WriteLogJob
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @return array
     */
    public function tags()
    {
        return [get_class($this), 'provider:' . $this->attributes['provider']];
    }

    public function handle()
    {
        RequestLog::query()->create($this->attributes);
    }
}
