<?php

namespace Gorilla\Laravel;

use Gorilla\Laravel\Commands\ClearCacheCommand;
use Gorilla\Laravel\Commands\WebsiteInfoCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

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
    private $configPath = __DIR__ . '/../config/gorilla.php';

    /**
     * Booting of service
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/gorilla.php' => $this->app->configPath().'/gorilla.php',
        ], 'config');
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
