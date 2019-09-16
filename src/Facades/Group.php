<?php

namespace Huangdijia\Youdu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Huangdijia\Youdu\Group
 * @method static array|bool getList(int|array $userId = 0)
 */
class Group extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'youdu.group';
    }
}