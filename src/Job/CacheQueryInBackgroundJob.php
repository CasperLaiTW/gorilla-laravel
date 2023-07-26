<?php

namespace Gorilla\Laravel\Job;

use Gorilla\Entities\GraphQL;
use Gorilla\Laravel\GorillaFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class CacheQueryInBackgroundJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var GraphQL
     */
    private $graphQL;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 300;
    /**
     * @var string
     */
    public $uniqueKey;

    public function __construct(string $uniqueKey, GraphQL $graphQL)
    {
        $this->graphQL = $graphQL;
        $this->uniqueKey = $uniqueKey;
    }

    public function handle()
    {
        $this->graphQL->bootCached();
        // trigger process to cache to new one
        GorillaFacade::queryByGraphQL($this->graphQL->withoutCacheContent());
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->uniqueKey))->dontRelease(),
            (new RateLimited('graphql-cache'))->dontRelease(),
        ];
    }

    /**
     * @return string
     */
    public function uniqueId(): string
    {
        return $this->uniqueKey;
    }
}
