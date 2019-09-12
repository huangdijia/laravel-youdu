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
        return 'youdu.' . config('youdu.default');
    }

    public static function app(string $name = 'default')
    {
        if (is_null(config('youdu.apps.' . $name, null))) {
            throw new \Exception("youdu.apps.{$name} is undefined");
        }

        return app('youdu.' . $name);
    }
}