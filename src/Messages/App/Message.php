<?php

namespace Huangdijia\Youdu\Messages\App;

use Huangdijia\Youdu\Contracts\AppMessage;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

abstract class Message implements AppMessage, Arrayable, Jsonable, JsonSerializable
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
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize()
    {
        $data = $this->toArray();

        if (is_null($this->toUser)) {
            unset($data['toUser']);
        }

        if (is_null($this->toDept)) {
            unset($data['toDept']);
        }

        return $data;
    }
}
