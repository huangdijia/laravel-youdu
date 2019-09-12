<?php

namespace Huangdijia\Youdu\Notifications;

use Huangdijia\Youdu\Messages\Text;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TextNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $message, $delay = null)
    {
        $this->queue   = config('youdu.notification.queue', 'youdu_notification');
        $this->delay   = $delay ?? config('youdu.notification.delay', 0);
        $this->tries   = config('youdu.notification.tries', 3);
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['youdu'];
    }

    /**
     * 獲取內容
     *
     * @param mixed $notificable
     * @return mixed
     */
    public function toYoudu($notificable)
    {
        return new Text('', '', $this->message);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'via'     => 'youdu',
            'message' => $this->message->toArray(),
        ];
    }
}
