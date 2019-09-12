<?php

namespace App\Channels;

use Huangdijia\Youdu\Facades\Youdu;
use Illuminate\Notifications\Notification;
use Huangdijia\Youdu\Messages\MessageInterface;

class YouduChannel
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

        $message->toUser($to);

        // Send notification to the $notifiable instance...
        return Youdu::send($message);
    }
}
