<?php

namespace Gorilla\Laravel;

use Gorilla\Client as BaseClient;
use Gorilla\Entities\GraphQL;
use Gorilla\Laravel\Job\CacheQueryInBackgroundJob;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Facades\Agent;

/**
 *
 */
class Client extends BaseClient
{
    /**
     * @param  array  $attributes
     * @return array
     */
    public function makeEnquiryFormData(array $attributes)
    {
        $baseAttribute = [
            'browser' => Agent::browser(),
            'device' => Agent::deviceType(),
            'operating_system' => Agent::platform(),
            'tracking_data' => collect(Session::get('tracking-data', []))->values()->toArray(),
        ];

        return array_merge($baseAttribute, $attributes);
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

        CacheQueryInBackgroundJob::dispatch($this->getGraphQLKey($graphQL), $graphQL)
            ->onQueue(config('gorilla.queue'));
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

    protected function getGraphQLKey(GraphQL $graphQL)
    {
        return md5(json_encode($graphQL->parameters()));
    }
}
