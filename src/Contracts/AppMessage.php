<?php
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Contracts;

interface AppMessage
{
    /**
     * Set users.
     */
    public function toUser(string $toUser);

    /**
     * Set Depts.
     */
    public function toDept(string $toDept);
}
