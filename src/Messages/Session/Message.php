<?php

namespace Huangdijia\Youdu\Messages\Session;

use Huangdijia\Youdu\Contracts\SessionMessage;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class Message implements SessionMessage, Arrayable, Jsonable
{
    protected $sender;
    protected $receiver;
    protected $sessionId;

    public function sender(string $sender = '')
    {
        $this->sender = $sender;
    }

    public function receiver(string $receiver = '')
    {
        $this->receiver = $receiver;
    }

    public function session(string $sessionId = '')
    {
        $this->sessionId = $sessionId;
    }

    public function toJson($options = 0)
    {
        $data = $this->toArray();

        if (!$this->receiver) {
            unset($data['receiver']);
        }

        if (!$this->sessionId) {
            unset($data['sessionId']);
        }

        return json_encode($data, $options);
    }
}
