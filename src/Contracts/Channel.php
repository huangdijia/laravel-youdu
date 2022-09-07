<?php
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Contracts;

use Illuminate\Notifications\Notification;

interface Channel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     */
    public function send($notifiable, Notification $notification);
}
