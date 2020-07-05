<?php

namespace App\Listeners\Common;

use App\Events\Common\WriteLogEvent;
use App\Jobs\Common\WriteLogJob;

class WriteLogListener
{
    /**
     * Handle the event.
     *
     * @param WriteLogEvent $writeLogEvent
     *
     * @return void
     */
    public function handle(WriteLogEvent $writeLogEvent)
    {
        $job = new WriteLogJob();
        $job->setAttributes($writeLogEvent->attributes);
        $job->onQueue('logging');

        dispatch($job);
    }
}
