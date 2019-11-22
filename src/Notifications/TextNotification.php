<?php

namespace Huangdijia\Youdu\Notifications;

use Huangdijia\Youdu\Messages\App\Text;

class TextNotification extends Notification
{
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $message, string $app = 'default', $delay = null)
    {
        parent::__construct(new Text($message), $app, $delay);
    }
}
