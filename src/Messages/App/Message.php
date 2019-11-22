<?php

namespace Huangdijia\Youdu\Messages\App;

use Huangdijia\Youdu\Contracts\AppMessage as MessageContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class Message implements MessageContract, Arrayable, Jsonable
{
    protected $toUser;
    protected $toDept;

    public function toUser(string $toUser)
    {
        $this->toUser = $toUser;
    }

    public function toDept(string $toDept)
    {
        $this->toDept = $toDept;
    }

    public function toJson($options = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->toArray(), $options);
    }
}
