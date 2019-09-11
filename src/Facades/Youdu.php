<?php

namespace Huangdijia\Youdu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method array getToken()
 * @method array identify()
 */
class Youdu extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'youdu';
    }
}