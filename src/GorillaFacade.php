<?php

namespace Gorilla\Laravel;

use Illuminate\Support\Facades\Facade;

/**
 * Class GorillaFacade
 *
 * @package Gorilla\Laravel
 *
 * @method static \Gorilla\Client|\Gorilla\GraphQL\Query query(string $name)
 * @method static \Gorilla\Client|\Gorilla\GraphQL\Mutation mutation(string $name)
 * @method static \Gorilla\Request setBaseUri(string $uri)
 * @method static \Gorilla\Client cache(string $seconds = null)
 * @method static \Gorilla\Client setDefaultCacheSecond(string $seconds)
 * @method static boolean isCacheEnabled()
 * @method static int getCacheSeconds()
 * @method static \GuzzleHttp\Psr7\Response\JsonResponse|string get()
 * @method static array makeEnquiryFormData(array $attributes)
 *
 * @see Client
 */
class GorillaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gorilla';
    }
}
