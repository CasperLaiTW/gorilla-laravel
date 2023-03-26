<?php

namespace Gorilla\Laravel\Http\Middlewares;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Facades\Agent;

class GorillaDashTrackingUrlParametersMiddleware
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET')) {
            $parameters = $request->query();
            $path = $request->path();

            $oldData = array_merge(Session::get('tracking-data', []), [
                'browser' => [
                    'parameter' => 'browser',
                    'value' => Agent::browser(),
                    'path' => null,
                    'session_at' => Carbon::now()->toDateTimeString()
                ],
                'device' => [
                    'parameter' => 'device',
                    'value' => Agent::deviceType(),
                    'path' => null,
                    'session_at' => Carbon::now()->toDateTimeString()
                ],
                'operating_system' => [
                    'parameter' => 'operating_system',
                    'value' => Agent::platform(),
                    'path' => null,
                    'session_at' => Carbon::now()->toDateTimeString()
                ],
            ]);

            $data = collect(collect($parameters))
                ->reject(function ($value, $parameter) {
                    return in_array($parameter, Config::get('gorilla.tracking_url_blacklist', []), true);
                })
                ->mapWithKeys(function($value, $parameter) use ($path) {
                    return [
                        "{$parameter}_{$value}_{$path}" => [
                            'parameter' => $parameter,
                            'value' => $value,
                            'path' => $path,
                            'session_at' => Carbon::now()->toDateTimeString(),
                        ]
                    ];
                })
                ->toArray();

            Session::put('tracking-data', array_merge($oldData, $data));
        }

        return $next($request);
    }
}
