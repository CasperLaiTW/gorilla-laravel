<?php

use Gorilla\Laravel\Listeners\CacheInBackgroundListener;

return [
    'id' => env('GORILLA_OAUTH_ID'),
    'token' => env('GORILLA_OAUTH_TOKEN'),
    'defaultCacheSeconds' => 60,
    'cacheDirectory' => storage_path('framework/cache'),
    'tracking_url_blacklist' => [
        'auto',
        'format',
        'width',
        'height',
    ],
    'queue' => 'default',
    'cache' => [
        'rate_limit_minutes' => 30,
    ],
    'listener' => [CacheInBackgroundListener::class, 'handle'],
];
