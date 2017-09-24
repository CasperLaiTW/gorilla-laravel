<?php

return [
    'id' => env('GORILLA_OAUTH_ID'),
    'token' => env('GORILLA_OAUTH_TOKEN'),
    'defaultCacheSeconds' => 60,
    'cacheDirectory' => storage_path('framework/cache'),
];