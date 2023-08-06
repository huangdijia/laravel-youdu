<?php

declare(strict_types=1);
/**
 * This file is part of huangdijia/laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Notifications;

use Huangdijia\Youdu\Messages\App\Text;

class TextNotification extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @param mixed|null $delay
     */
    public function __construct(string $message, string $app = 'default', ?int $delay = null)
    {
        parent::__construct(new Text($message), $app, $delay);
    }
}
