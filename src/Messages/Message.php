<?php

namespace Huangdijia\Youdu\Messages;

use Huangdijia\Youdu\Contracts\Message as MessageContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class Message implements MessageContract, Arrayable, Jsonable
{
    protected $toUser = '';
    protected $toDept = '';

    public function toUser(string $toUser = '')
    {
        $this->toUser = $toUser;
    }

    public function toDept(string $toDept = '')
    {
        $this->toDept = $toDept;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
