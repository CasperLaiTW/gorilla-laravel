<?php

namespace Gorilla\Laravel\Commands;

use Gorilla\Laravel\GorillaFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use const Grpc\STATUS_OUT_OF_RANGE;

class WebsiteInfoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gorilla:website-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache website info';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \phpFastCache\Exceptions\phpFastCacheDriverCheckException
     * @throws \phpFastCache\Exceptions\phpFastCacheInvalidArgumentException
     * @throws \phpFastCache\Exceptions\phpFastCacheInvalidConfigurationException
     */
    public function handle()
    {
        $data = GorillaFacade::query('websiteInfo')
            ->fields([
                'url',
                'base_products_path',
                'base_categories_path',
                'base_ranges_path',
                'base_tribes_path',
            ])
            ->get()
            ->json('data');
        Storage::put('website-info.json', json_encode($data['websiteInfo']));
        $this->comment('Cache website info to '.Storage::path('website-info.json'));
        return 0;
    }
}
