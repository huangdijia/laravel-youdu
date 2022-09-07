<?php

declare(strict_types=1);
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Huangdijia\Youdu\App
 * @method static mixed config(?stirng $key = null, $default = null)
 * @method static \Huangdijia\Youdu\App app(string $name)
 * @method static array apps()
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
 * @method static string getAccessToken()
 * @method static bool send(\Huangdijia\Youdu\Contracts\AppMessage $message)
 * @method static bool sendToUser(string $toUser = '', $message = '')
 * @method static bool sendToDept(string $toDept = '', $message = '')
 * @method static bool sendToAll(\Huangdijia\Youdu\Messages\App\SysMsg $message, bool $onlineOnly = false)
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
