<?php

namespace Gorilla\Laravel;

use Gorilla\Client;
use Gorilla\Laravel\Commands\ClearCacheCommand;
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
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverCheckException
     * @throws \phpFastCache\Exceptions\phpFastCacheInvalidArgumentException
     * @throws \phpFastCache\Exceptions\phpFastCacheInvalidConfigurationException
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath, 'gorilla');
        $this->app->singleton('gorilla', function ($app) {
            $client = new Client(config('gorilla.id'), config('gorilla.token'));
            $client->setCachePath(config('gorilla.cacheDirectory'));
            $client->setDefaultCacheSecond(config('gorilla.defaultCacheSeconds'));
            return $client;
        });
        $this->registerCommands();
    }

    /**
     *
     */
    private function registerCommands()
    {
        $this->commands([
            ClearCacheCommand::class,
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
