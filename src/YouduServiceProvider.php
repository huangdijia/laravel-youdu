<?php
/**
 * This file is part of Hyperf.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/master/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Channels\YouduChannel;
use Huangdijia\Youdu\Contracts\HttpClient;
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
            return new Manager(config('youdu'));
        });
        $this->app->alias(Manager::class, 'youdu.manager');

        $this->app->bind(HttpClient::class, function ($app) {
            $driver = config('youdu.http.driver', \Huangdijia\Youdu\Http\Guzzle::class);

            if (! class_exists($driver)) {
                $driver = \Huangdijia\Youdu\Http\Guzzle::class;
            }

            return $this->app->make($driver, [
                'baseUri' => (string) config('youdu.api', ''),
                'timeout' => (int) config('youdu.timeout', 2),
                'options' => (array) config('youdu.http.options', []),
            ]);

            // return new Guzzle(
            //     config('youdu.api'),
            //     (int) config('youdu.timeout', 2),
            //     (array) config('youdu.http.options', [])
            // );
        });
        $this->app->alias(HttpClient::class, 'youdu.http.client');

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
                HttpClient::class,
                'youdu.http.client',
            ])
            ->all();
    }
}
