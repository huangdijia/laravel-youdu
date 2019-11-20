<?php

namespace Huangdijia\Youdu\Channels;

use Huangdijia\Youdu\Contracts\Channel;
use Huangdijia\Youdu\Contracts\Message;
use Huangdijia\Youdu\Facades\Youdu as YouduFacade;
use Illuminate\Notifications\Notification;

class YouduChannel implements Channel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toYoudu($notifiable);
        $app     = is_callable([$notification, 'app']) ? $notification->app() : '';

        if (
            !($to = $notifiable->routeNotificationFor('youdu', $notification))
            || !($message instanceof Message)
        ) {
            return;
        }

        return YouduFacade::app($app)->send($to, '', $message);
    }
}
