<?php

namespace Gorilla\Laravel;

use Illuminate\Support\Facades\Facade;

class GorillaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'gorilla';
    }
}