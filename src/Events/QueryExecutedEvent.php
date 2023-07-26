<?php

namespace Gorilla\Laravel\Events;

use Gorilla\Entities\GraphQL;
use Illuminate\Foundation\Events\Dispatchable;

class QueryExecutedEvent
{
    use Dispatchable;

    /**
     * @var string
     */
    public $uniqueKey;
    /**
     * @var GraphQL
     */
    public $graphQL;

    public function __construct(string $uniqueKey, GraphQL $graphQL)
    {
        $this->uniqueKey = $uniqueKey;
        $this->graphQL = $graphQL;
    }
}
