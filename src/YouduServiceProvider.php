<?php

namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Channels\YouduChannel;
use Huangdijia\Youdu\Http\Guzzle;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class YouduServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config' => $this->app->basePath('config')], 'config');

            $this->commands([
                Console\InstallCommand::class,
                Console\SendToUserCommand::class,
                Console\SendToDeptCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/youdu.php', 'youdu');

        $this->app->bind(Manager::class, function ($app) {
            return new Manager($app['config']['youdu']);
        });

        $this->app->alias(Manager::class, 'youdu.manager');

        $this->app->bind('youdu.http.client', function ($app) {
            return new Guzzle($app['config']['youdu.api'], (int) $app['config']['youdu.timeout'] ?? 2);
        });

        $this->app->make(ChannelManager::class)->extend('youdu', function ($app) {
            return $app->make(YouduChannel::class);
        });

        $this->app['translator']->addJsonPath(__DIR__ . '/../resources/lang');
    }

    public function provides()
    {
        return collect(config('youdu.apps', []))
            ->keys()
            ->transform(function ($app, $key) {
                return Str::start($app, 'youdu.');
            })
            ->merge([
                Manager::class,
                'youdu.manager',
                'youdu.http.client',
            ])
            ->all();
    }
}
