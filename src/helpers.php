<?php

use Gorilla\Laravel\GorillaDashUrl;

if (!function_exists('gorilla_url')) {
    function gorilla_url() {
        return app(GorillaDashUrl::class);
    }
}
