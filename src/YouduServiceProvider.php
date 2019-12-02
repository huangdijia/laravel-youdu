<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Channels\YouduChannel;
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

        $this->app->singleton(Client::class, function ($app) {
            return new Client();
        });

        $this->app->alias(Client::class, 'youdu.http.client');

        $this->app->make(ChannelManager::class)->extend('youdu', function ($app) {
            return $app->make(YouduChannel::class);
        });
    }

    public function provides()
    {
        return collect(config('youdu.apps', []))
            ->merge([
                Client::class,
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
