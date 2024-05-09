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
        $data = blank(Session::get('tracking-data')) ? [] : Session::get('tracking-data');

        $baseAttribute = [
            'browser' => Agent::browser(),
            'device' => Agent::deviceType(),
            'operating_system' => Agent::platform(),
            'tracking_data' => collect($data)->values()->toArray() ?: [],
        ];

        return array_merge($baseAttribute, $attributes);
    }
}
