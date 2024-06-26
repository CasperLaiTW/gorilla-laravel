<?php

namespace Gorilla\Laravel;

use Gorilla\Client as BaseClient;
use Gorilla\Entities\GraphQL;
use Gorilla\Laravel\Events\QueryExecutedEvent;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Facades\Agent;

/**
 *
 */
class Client extends BaseClient
{
    /**
     * @var bool
     */
    private $ignoreCache = false;

    /**
     * @param  array  $attributes
     * @return array
     */
    public function makeEnquiryFormData(array $attributes)
    {
        $data = blank(Session::get('tracking-data')) ? [] : Session::get('tracking-data');

        $baseAttribute = [
            'browser' => Agent::browser(),
            'device' => Agent::deviceType(),
            'operating_system' => Agent::platform(),
            'tracking_data' => collect($data)->values()->toArray() ?: [],
        ];

        return array_merge($baseAttribute, $attributes);
    }


    /**
     * @param $value
     * @return $this
     */
    public function ignoreCache($value)
    {
        $this->ignoreCache = $value;
        return $this;
    }

    /**
     * @return \Gorilla\Response\JsonResponse|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverCheckException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get()
    {
        $graphQL = (new GraphQL($this->queries))
            ->setHandleCacheByClient(true)
            ->cache(31536000);

        if ($this->ignoreCache) {
            $graphQL->withoutCacheContent();
        }

        if ($graphQL->isQuery()) {
            QueryExecutedEvent::dispatch($this->getGraphQLKey($graphQL), $graphQL);
        }
        $response = $this->request->request($graphQL);

        $this->queries->reset();
        return $response;
    }

    /**
     * @param  GraphQL  $graphQL
     * @return \Gorilla\Response\JsonResponse|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheDriverCheckException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidArgumentException
     * @throws \Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function queryByGraphQL(GraphQL $graphQL)
    {
        return $this->request->request($graphQL);
    }

    /**
     * @return \Gorilla\GraphQL\Collection
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @param  GraphQL  $graphQL
     * @return string
     */
    protected function getGraphQLKey(GraphQL $graphQL)
    {
        return md5(json_encode($graphQL->parameters()));
    }
}
