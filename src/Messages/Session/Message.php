<?php

namespace Huangdijia\Youdu\Messages\Session;

use Huangdijia\Youdu\Contracts\SessionMessage;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

abstract class Message implements SessionMessage, Arrayable, Jsonable, JsonSerializable
{
    protected $sender;
    protected $receiver;
    protected $sessionId;

    public function sender(string $sender)
    {
        $this->sender = $sender;
    }

    public function receiver(string $receiver)
    {
        $this->receiver = $receiver;
    }

    public function session(string $sessionId)
    {
        $this->sessionId = $sessionId;
    }

    public function toJson($options = JSON_UNESCAPED_UNICODE)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize()
    {
        $data = $this->toArray();

        if (is_null($this->receiver) && isset($data['receiver'])) {
            unset($data['receiver']);
        }

        if (is_null($this->sessionId) && isset($data['sessionId'])) {
            unset($data['sessionId']);
        }

        return $data;
    }
}
