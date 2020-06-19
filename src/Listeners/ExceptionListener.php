<?php

namespace Huangdijia\Youdu\Listeners;

use Huangdijia\Youdu\Notifications\TextNotification;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Notification;
use Throwable;

class ExceptionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(MessageLogged $event)
    {
        if (app()->environment(config('youdu.exception.ignore_environments', 'local'))) {
            return;
        }

        if (!isset($event->context['exception'])) {
            return;
        }

        $e = $event->context['exception'];

        // $message = sprintf(
        //     "【%s Exception Reporting】\n\nEnvironment: %s\n%sUrl: %s\nException: %s\nMessage: %s\nPosition: %s:%s\nTime: %s\n\n",
        //     config('app.name'),
        //     config('app.env'),
        //     $this->getCurrentBranch(),
        //     request()->fullUrl(),
        //     get_class($e),
        //     $e->getMessage(),
        //     $e->getFile(),
        //     $e->getLine(),
        //     now()->toDateTimeString()
        // );

        $messages = [
            __('youdu.environment') => config('app.env'),
            __('youdu.branch')      => $this->getCurrentBranch(),
            __('youdu.url')         => request()->fullUrl(),
            __('youdu.exception')   => get_class($e),
            __('youdu.message')     => $e->getMessage(),
            __('youdu.position')    => $e->getFile() . ':' . $e->getLine(),
            __('youdu.time')        => now()->toDateTimeString(),
        ];

        $message = sprintf(
            "[%s %s %s]\n\n%s",
            config('app.name'),
            __('youdu.exception'),
            __('youdu.reporting'),
            collect($messages)
                ->transform(function ($item, $key) {
                    return "{$key}: {$item}";
                })
                ->join("\n")
        );

        try {
            collect(config('youdu.exception.receivers', []))
                ->transform(function ($route) {
                    return Notification::route('youdu', $route);
                })
                ->tap(function ($notifiables) use ($message) {
                    /** @var Collection $notifiables */
                    if ($notifiables->isEmpty()) {
                        return;
                    }

                    if (config('youdu.exception.report_now')) {
                        Notification::sendNow($notifiables, new TextNotification($message, config('youdu.exception.report_app', 'default')));
                    } else {
                        Notification::send($notifiables, new TextNotification($message, config('youdu.exception.report_app', 'default')));
                    }

                });
        } catch (Throwable $e) {
            info($e->getMessage(), [
                'method'   => __METHOD__,
                'position' => __FILE__ . ':' . __LINE__,
            ]);
        }

        return true;
    }

    /**
     * 获取当前分支名
     * @return string
     * @throws BindingResolutionException
     */
    private function getCurrentBranch()
    {
        if (!config('youdu.exception.show_git_branch', false)) {
            return '';
        }

        if (!is_file($headFile = app()->basePath(".git/HEAD"))) {
            return '';
        }

        $headContent   = file_get_contents($headFile);
        $currentBranch = trim(substr($headContent, 16));

        return $currentBranch;
    }
}
