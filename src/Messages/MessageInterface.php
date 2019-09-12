<?php

namespace Huangdijia\Youdu\Messages;

interface MessageInterface
{
    public function toUser($toUser = '');
    public function toDept($toDept = '');
}
