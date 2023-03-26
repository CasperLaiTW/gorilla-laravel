<?php

namespace Gorilla\Laravel;

use Gorilla\Client as BaseClient;
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
            'tracking_data' => collect(Session::get('tracking-data', []))->values()->toArray(),
        ];

        return array_merge($baseAttribute, $attributes);
    }
}
