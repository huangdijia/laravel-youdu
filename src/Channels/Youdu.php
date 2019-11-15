<?php

namespace Huangdijia\Youdu\Channels;

use Huangdijia\Youdu\Contracts\Channel;
use Huangdijia\Youdu\Facades\Youdu as YouduFacace;
use Huangdijia\Youdu\Messages\MessageInterface;
use Illuminate\Notifications\Notification;

class Youdu implements Channel
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

        if (
            !($to = $notifiable->routeNotificationFor('youdu', $notification))
            || !($message instanceof MessageInterface)
        ) {
            return;
        }

        // Send notification to the $notifiable instance...
        return YouduFacace::send($to, '', $message);
    }
}
