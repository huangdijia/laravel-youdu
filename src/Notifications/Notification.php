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

use Huangdijia\Youdu\Contracts\AppMessage;
use Huangdijia\Youdu\Messages\App\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as BaseNotification;

class Notification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public int $tries;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected AppMessage $message, protected string $app = 'default', ?int $delay = null)
    {
        $this->delay = $delay ?? (int) config('youdu.notification.delay', 0);
        $this->tries = (int) config('youdu.notification.tries', 3);
        $this->queue = config('youdu.notification.queue', 'youdu_notification');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
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
     * Get the notification's message.
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
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'via' => 'youdu',
            'app' => $this->app,
            'message' => $this->message->toArray(),
        ];
    }
}
