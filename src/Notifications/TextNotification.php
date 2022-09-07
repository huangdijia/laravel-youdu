<?php
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Notifications;

use Huangdijia\Youdu\Messages\App\Text;

class TextNotification extends Notification
{
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @param null|mixed $delay
     */
    public function __construct(string $message, string $app = 'default', $delay = null)
    {
        parent::__construct(new Text($message), $app, $delay);
    }
}
