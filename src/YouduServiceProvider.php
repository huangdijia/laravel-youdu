<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Http\Curl;
use Huangdijia\Youdu\Http\Guzzle;
use Illuminate\Support\ServiceProvider;
use Huangdijia\Youdu\Channels\YouduChannel;
use Illuminate\Notifications\ChannelManager;

class YouduServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/youdu.php' => $this->app->basePath('config/youdu.php')]);

            $this->commands([
                Console\SendToUserCommand::class,
                Console\SendToDeptCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/youdu.php', 'youdu');

        $this->app->singleton(Manager::class, function ($app) {
            return new Manager(config('youdu'));
        });

        $this->app->alias(Manager::class, 'youdu.manager');

        $this->app->singleton('youdu.http.client', function ($app) {
            // return new Curl(config('youdu.api'));
            return new Guzzle(config('youdu.api'));
        });

        $this->app->make(ChannelManager::class)->extend('youdu', function ($app) {
            return $app->make(YouduChannel::class);
        });
    }

    public function provides()
    {
        return collect(config('youdu.apps', []))
            ->merge([
                Manager::class,
                'youdu.manager',
                'youdu.http.client',
            ])
            ->transform(function ($item, $app) {
                return 'youdu.' . $app;
            })
            ->all();
    }
}
