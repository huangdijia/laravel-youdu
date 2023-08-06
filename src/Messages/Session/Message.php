<?php

declare(strict_types=1);
/**
 * This file is part of huangdijia/laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Messages\Session;

use Huangdijia\Youdu\Contracts\SessionMessage;

abstract class Message implements SessionMessage
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
