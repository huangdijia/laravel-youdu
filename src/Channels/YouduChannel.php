<?php

namespace Huangdijia\Youdu\Channels;

use Throwable;
use Huangdijia\Youdu\Facades\Youdu;
use Huangdijia\Youdu\Contracts\Channel;
use Huangdijia\Youdu\Contracts\AppMessage;
use Illuminate\Notifications\Notification;
use Huangdijia\Youdu\Exceptions\ChannelException;

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
        try {
            $message = $notification->toYoudu($notifiable);
            $app     = is_callable([$notification, 'app']) ? $notification->app($notifiable) : '';

            if (
                !($to = $notifiable->routeNotificationFor('youdu', $notification))
                || !($message instanceof AppMessage)
            ) {
                return false;
            }

            return Youdu::app($app)->sendToUser($to, $message);
        } catch (Throwable $e) {
            throw new ChannelException($e->getMessage(), 1);
        }
    }
}
