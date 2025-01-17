<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OpenXML extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'openxml';
    }
}
