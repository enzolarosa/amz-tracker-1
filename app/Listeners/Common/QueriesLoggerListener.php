<?php

namespace App\Listeners\Common;

use App\Events\Common\QueriesLoggerEvent;
use App\Jobs\Common\QueriesLoggerJob;

class QueriesLoggerListener
{
    public function handle(QueriesLoggerEvent $event)
    {
        dispatch(
            (new QueriesLoggerJob())
                ->setSql($event->getSql())
                ->setTime($event->getTime())
                ->setBindings($event->getBindings())
                ->onQueue('queries')
        );
    }
}
