<?php

namespace Huangdijia\Youdu\Messages\Items;

use Huangdijia\Youdu\Contracts\MessageItem;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Item implements MessageItem, Arrayable, Jsonable
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
