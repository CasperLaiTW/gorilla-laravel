<?php

namespace Gorilla\Laravel;

use Gorilla\Laravel\Commands\ClearCacheCommand;
use Gorilla\Laravel\Commands\WebsiteInfoCommand;
use Gorilla\Laravel\Events\QueryExecutedEvent;
use Gorilla\Laravel\Listeners\CacheInBackgroundListener;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;

/**
 * Class GorillaServiceProvider
 *
 * @package Gorilla\Laravel
 */
class GorillaServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    private $configPath = __DIR__.'/../config/gorilla.php';

    /**
     * Booting of service
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/gorilla.php' => $this->app->configPath().'/gorilla.php',
        ], 'config');
        RateLimiter::for('graphql-cache', function (object $job) {
            return Limit::perMinutes(
                Config::get('gorilla.cache.rate_limit_minutes', 30),
                1
            )
                ->by($job->uniqueKey);
        });

        if (Config::get('gorilla.listener')) {
            Event::listen(
                QueryExecutedEvent::class,
                Config::get('gorilla.listener')
            );
        }
    }

    /**
     * Register service
     *
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath, 'gorilla');
        $this->app->singleton('gorilla', function ($app) {
            $client = new Client(Config::get('gorilla.id'), Config::get('gorilla.token'));
            $client->setCachePath(Config::get('gorilla.cacheDirectory'));
            $client->setDefaultCacheSecond(Config::get('gorilla.defaultCacheSeconds'));
            return $client;
        });
        $this->registerCommands();
        $this->app->singleton(GorillaDashUrl::class, function () {
            return new GorillaDashUrl();
        });
    }

    /**
     *
     */
    private function registerCommands()
    {
        $this->commands([
            ClearCacheCommand::class,
            WebsiteInfoCommand::class,
        ]);
    }

    /**
     * @return array
     */
    public function providers()
    {
        return [
            'gorilla',
        ];
    }
}
