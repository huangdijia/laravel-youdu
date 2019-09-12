<?php

namespace Huangdijia\Youdu\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Huangdijia\Youdu\Dept
 * @method static array|bool getList(int $parentDeptId = 0)
 */
class Dept extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'youdu.dept';
    }
}