<?php

namespace App\Events\Common;

class WriteLogEvent
{
    public array $attributes;

    public string $provider;
    public $request;
    public $response;

    /**
     * WriteRequestLog constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }
}
