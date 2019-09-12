<?php

namespace Huangdijia\Youdu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Huangdijia\Youdu\AccessToken
 * @method static string get(string $appId = '')
 */
class AccessToken extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'youdu.access_token';
    }
}