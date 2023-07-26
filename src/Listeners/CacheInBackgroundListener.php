<?php

namespace Gorilla\Laravel\Listeners;

use Gorilla\Laravel\Events\QueryExecutedEvent;
use Gorilla\Laravel\Job\CacheQueryInBackgroundJob;

class CacheInBackgroundListener
{
    public function __construct()
    {
    }

    public function handle(QueryExecutedEvent $event)
    {
        CacheQueryInBackgroundJob::dispatch($event->uniqueKey, $event->graphQL)
            ->onQueue(config('gorilla.queue'));
    }
}
