<?php
/**
 * Created by PhpStorm.
 * User: casperlai
 * Date: 2017/9/24
 * Time: 上午10:29
 */

namespace Gorilla\Laravel\Commands;

use Gorilla\Laravel\GorillaFacade;
use Illuminate\Console\Command;
use phpFastCache\CacheManager;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gorilla:clear-cache';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cache file';

    /**
     *
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverCheckException
     * @throws \phpFastCache\Exceptions\phpFastCacheInvalidConfigurationException
     * @throws \phpFastCache\Exceptions\phpFastCacheInvalidArgumentException
     */
    public function handle()
    {
        CacheManager::setDefaultConfig([
            'path' => config('gorilla.cacheDirectory'),
            'ignoreSymfonyNotice' => true,
        ]);
        CacheManager::getInstance('files')->clear();
        $this->comment('Clear cache success');
    }
}