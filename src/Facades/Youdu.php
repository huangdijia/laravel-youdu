<?php

namespace Huangdijia\Youdu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Huangdijia\Youdu\App
 * @method static int getBuin()
 * @method static string getAppId()
 * @method static string getAesKey()
 * @method static \Huangdijia\Youdu\Dept dept()
 * @method static \Huangdijia\Youdu\Group group()
 * @method static \Huangdijia\Youdu\User user()
 * @method static \Huangdijia\Youdu\Session session()
 * @method static \Huangdijia\Youdu\Media media()
 * @method string url(string $uri = '', bool $withAccessToken = true)
 * @method string encryptMsg(string $unencrypted = '')
 * @method string decryptMsg(?string $encrypted)
 * @method static bool send(string $toUser = '', string $toDept = '', $message = '')
 * @method static bool sendToUser(string $toUser = '', $message = '')
 * @method static bool sendToDept(string $toDept = '', $message = '')
 * @method static string getAccessToken()
 * @method static bool setNoticeCount(string $account = '', string $tip = '', int $msgCount = 0)
 * @method static bool popWindow(string $toUser = '', string $toDept = '', PopWindow $message)
 */
class Youdu extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'youdu.manager';
    }
}