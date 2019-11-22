<?php

namespace Huangdijia\Youdu\Contracts;

interface SessionMessage
{
    public function sender(string $sender);
    public function receiver(string $receiver);
    public function session(string $sessionId);
}
