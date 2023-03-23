<?php

namespace Gorilla\Laravel\Http\Middlewares;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

            $data = Session::get('tracking-data', []);
            foreach ($parameters as $parameter => $value) {
                $data["{$parameter}_{$value}_{$path}"] = [
                    'parameter' => $parameter,
                    'value' => $value,
                    'path' => $path,
                    'created_at' => Carbon::now(),
                ];
            }
            Session::put('tracking-data', $data);
        }

        return $next($request);
    }
}
