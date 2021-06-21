<?php
/**
 * This file is part of Hyperf.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/master/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Messages\App\Items;

use Huangdijia\Youdu\Contracts\AppMessageItem;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Item implements AppMessageItem, Arrayable, Jsonable
{
    protected $items = [];

    /**
     * 转成 array.
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * 转成 json.
     * @param mixed $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->items, $options);
    }
}
