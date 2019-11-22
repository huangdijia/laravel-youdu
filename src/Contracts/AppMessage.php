<?php

namespace Huangdijia\Youdu\Contracts;

interface AppMessage
{
    /**
     * Set users
     *
     * @param string $toUser
     * @return void
     */
    public function toUser(string $toUser);
    /**
     * Set Depts
     *
     * @param string $toDept
     * @return void
     */
    public function toDept(string $toDept);
}
