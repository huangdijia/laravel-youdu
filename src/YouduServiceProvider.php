<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Channels\Youdu as YouduChannel;
use Huangdijia\Youdu\Http\Client;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;

class YouduServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/youdu.php' => $this->app->basePath('config/youdu.php')]);

            $this->commands([
                Console\SendCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/youdu.php', 'youdu');

        $this->app->singleton('youdu.manager', function () {
            return new Manager(config('youdu'));
        });

        $this->app->singleton('youdu.http.client', function () {
            return new Client;
        });

        $this->app->make(ChannelManager::class)->extend('youdu', function ($app) {
            return $app->make(YouduChannel::class);
        });
    }

    public function provides()
    {
        return collect(config('youdu.apps', []))
            ->transform(function ($item, $name) {
                return 'youdu.' . $name;
            })
            ->all();
    }
}
