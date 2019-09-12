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
                Console\SendCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/youdu.php', 'youdu');

        foreach (config('youdu.apps', []) as $name => $config) {
            $this->app->singleton('youdu.' . $name, function () use ($config) {
                return new Youdu(config('youdu.api'), (int) config('youdu.buin'), $config['app_id'], $config['ase_key']);
            });
        }

        $this->app->singleton('youdu.http.client', function () {
            return new Client;
        });

        $this->app->singleton('youdu.access_token', function() {
            return new AccessToken;
        });

        $this->app->singleton('youdu.dept', function() {
            return new Dept();
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
