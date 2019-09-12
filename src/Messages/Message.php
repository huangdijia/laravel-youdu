<?php

namespace Huangdijia\Youdu\Messages;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class Message implements Arrayable, Jsonable, MessageInterface
{
    public function toUser($toUser = '')
    {
        $this->toUser = $toUser;
    }

    public function toDept($toDept = '')
    {
        $this->toDept = $toDept;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
