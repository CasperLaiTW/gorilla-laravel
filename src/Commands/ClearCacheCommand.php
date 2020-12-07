<?php
/**
 * Created by PhpStorm.
 * User: casperlai
 * Date: 2017/9/24
 * Time: 上午10:29
 */

namespace Gorilla\Laravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

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
     */
    public function handle()
    {
        tap(new Filesystem, function (Filesystem $file) {
            collect($file->directories(config('gorilla.cacheDirectory')))->each(function ($directory) use ($file) {
                $file->cleanDirectory($directory);
            });
        });
        $this->comment('Clear cache success');
    }
}
