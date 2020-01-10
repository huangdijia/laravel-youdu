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

    /**
     * 发送至用户
     * @param string $toUser 
     * @return void 
     */
    public function toUser(string $toUser)
    {
        $this->toUser = $toUser;
    }

    /**
     * 发送至部门
     * @param string $toDept 
     * @return void 
     */
    public function toDept(string $toDept)
    {
        $this->toDept = $toDept;
    }

    /**
     * 转成 json
     * @param int $options 
     * @return string|false 
     */
    public function toJson($options = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * json 序列化
     * @return array 
     */
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
