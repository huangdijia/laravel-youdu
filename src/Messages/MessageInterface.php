<?php

namespace Huangdijia\Youdu\Messages;

interface MessageInterface
{
    public function toUser(string $toUser = '');
    public function toDept(string $toDept = '');
}
