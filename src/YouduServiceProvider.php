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

        $this->app->singleton(Manager::class, function ($app) {
            return new Manager(config('youdu'));
        });

        $this->app->alias(Manager::class, 'youdu.manager');

        $this->app->singleton('youdu.http.client', function ($app) {
            return new Guzzle(
                (string) config('youdu.api'),
                (int) config('youdu.timeout', 2),
                (array) config('youdu.http.options', [])
            );
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
