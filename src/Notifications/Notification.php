<?php

namespace Huangdijia\Youdu\Notifications;

use Huangdijia\Youdu\Contracts\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as BaseNotification;

class Notification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    protected $app;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message, string $app = 'default', ?int $delay = null)
    {
        $this->message = $message;
        $this->app     = $app;

        $this->delay = $delay ?? config('youdu.notification.delay', 0);
        $this->tries = config('youdu.notification.tries', 3);
        $this->queue = config('youdu.notification.queue', 'youdu_notification');
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
     * Get the notification's delivery youdu app.
     *
     * @return string
     */
    public function app()
    {
        return $this->app;
    }

    /**
     * Get the notification's message
     *
     * @param mixed $notificable
     * @return mixed
     */
    public function toYoudu($notificable)
    {
        return $this->message;
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
            'app'     => $this->app,
            'message' => $this->message->toArray(),
        ];
    }
}
