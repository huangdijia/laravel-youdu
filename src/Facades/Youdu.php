<?php

namespace Huangdijia\Youdu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Huangdijia\Youdu\Youdu
 * @method static string getAppId()
 * @method static string getAesKey()
 * @method static string getAccessToken()
 * @method static bool send(string $toUser = '', string $toDept = '', $message = '')
 * @method static bool sendToUser(string $toUser = '', $message = '')
 * @method static bool sendToDept(string $toDept = '', $message = '')
 * @method static string uploadFile(string $file = '', string $fileType = 'file')
 * @method static bool downloadFile(string $mediaId = '', ?string $savePath = null)
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