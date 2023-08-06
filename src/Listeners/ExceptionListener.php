<?php

declare(strict_types=1);
/**
 * This file is part of huangdijia/laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Listeners;

use Huangdijia\Youdu\Notifications\TextNotification;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Throwable;

class ExceptionListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param object $event
     */
    public function handle(MessageLogged $event)
    {
        if (app()->environment(config('youdu.exception.ignore_environments', 'local'))) {
            return;
        }

        if (! isset($event->context['exception'])) {
            return;
        }

        $message = Str::start(
            $this->assembleMessage($event->context['exception'], app()->runningInConsole()),
            __('youdu.title', ['name' => config('app.name')]) . "\n\n"
        );

        try {
            collect(config('youdu.exception.receivers', []))
                ->transform(fn ($route) => Notification::route('youdu', $route))
                ->whenNotEmpty(function ($notifiables) use ($message) {
                    /* @var Collection $notifiables */

                    if (config('youdu.exception.report_now')) {
                        Notification::sendNow($notifiables, new TextNotification($message, config('youdu.exception.report_app', 'default')));
                    } else {
                        Notification::send($notifiables, new TextNotification($message, config('youdu.exception.report_app', 'default')));
                    }
                });
        } catch (Throwable $e) {
            info($e->getMessage(), [
                'method' => __METHOD__,
                'position' => __FILE__ . ':' . __LINE__,
            ]);
        }

        return true;
    }

    /**
     * Assemble message.
     * @param bool $runningInConsole
     * @return string
     * @throws BindingResolutionException
     * @throws UnitException
     */
    protected function assembleMessage(Throwable $e, $runningInConsole = false)
    {
        return collect()
            ->put(__('youdu.environment'), config('app.env'))
            ->when($this->getCurrentBranch(), fn ($collection, $branch) => /* @var \Illuminate\Support\Collection $collection */
$collection->put(__('youdu.branch'), $branch))
            ->when(! $runningInConsole, fn ($collection) => /* @var \Illuminate\Support\Collection $collection */
$collection->put(__('youdu.url'), app('request')->fullUrl()))
            ->put(__('youdu.exception'), $e::class)
            ->put(__('youdu.message'), $e->getMessage())
            ->put(__('youdu.position'), $e->getFile() . ':' . $e->getLine())
            ->when(defined('LARAVEL_START'), fn ($collection) => /* @var \Illuminate\Support\Collection $collection */
$collection->put(__('youdu.usetime'), number_format(microtime(true) - LARAVEL_START, 3)))
            ->put(__('youdu.time'), date('Y-m-d H:i:s'))
            ->transform(fn ($value, $key) => sprintf('%s: %s', $key, $value))
            ->join("\n");
    }

    /**
     * Get git current branch name.
     * @return string
     * @throws BindingResolutionException
     */
    private function getCurrentBranch()
    {
        if (! config('youdu.exception.show_git_branch', false)) {
            return '';
        }

        if (! is_file($headFile = app()->basePath('.git/HEAD'))) {
            return '';
        }

        $headContent = file_get_contents($headFile);
        return trim(substr($headContent, 16));
    }
}
