<?php

namespace Gorilla\Laravel;

use Illuminate\Support\Facades\Storage;
use Spatie\Url\Url;

class GorillaDashUrl
{
    /**
     * @var array
     */
    private $config;

    /**
     */
    public function __construct()
    {
        if (!Storage::exists('website-info.json')) {
            throw new \RuntimeException('Missing website info json. try to run gorilla:website-info');
        }
        $this->config = json_decode(Storage::get('website-info.json'), true);
    }

    /**
     * @param $path
     * @return string
     */
    public function getBaseUrl($path)
    {
        return Url::fromString($this->config['url'])
            ->withPath($path)
            ->__toString();
    }

    /**
     * @param $path
     * @return string
     */
    public function getProductUrl($path)
    {
        return Url::fromString($this->config['url'])
            ->withPath($this->config['base_products_path'].$path)
            ->__toString();
    }

    /**
     * @param $path
     * @return string
     */
    public function getProductCategoryUrl($path)
    {
        return Url::fromString($this->config['url'])
            ->withPath($this->config['base_categories_path'].$path)
            ->__toString();
    }

    /**
     * @param $path
     * @return string
     */
    public function getProductRangeUrl($path)
    {
        return Url::fromString($this->config['url'])
            ->withPath($this->config['base_ranges_path'].$path)
            ->__toString();
    }

    /**
     * @param $path
     * @return string
     */
    public function getTribeUrl($path)
    {
        return Url::fromString($this->config['url'])
            ->withPath($this->config['base_tribes_path'].$path)
            ->__toString();
    }
}
