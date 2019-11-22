<?php

namespace Huangdijia\Youdu\Messages\App\Items;

use Huangdijia\Youdu\Contracts\AppMessageItem;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Item implements AppMessageItem, Arrayable, Jsonable
{
    protected $items = [];

    public function toArray()
    {
        return $this->items;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->items, $options);
    }
}
