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

        $this->app->when(Manager::class)
            ->needs('$config')
            ->give(config('youdu'));

        $this->app->singleton('youdu.manager', function ($app) {
            return $app->make(Manager::class);
        });

        $this->app->singleton('youdu.http.client', function ($app) {
            return $app->make(Client::class);
        });

        $this->app->make(ChannelManager::class)->extend('youdu', function ($app) {
            return $app->make(YouduChannel::class);
        });

        $this->compat();
    }

    protected function compat()
    {
        $aliases = [
            "Huangdijia\\Youdu\\App"                          => "Huangdijia\\Youdu\\Youdu",
            "Huangdijia\\Youdu\\Messages\\App\\Exlink"        => "Huangdijia\\Youdu\\Messages\\Exlink",
            "Huangdijia\\Youdu\\Messages\\App\\File"          => "Huangdijia\\Youdu\\Messages\\File",
            "Huangdijia\\Youdu\\Messages\\App\\Image"         => "Huangdijia\\Youdu\\Messages\\Image",
            "Huangdijia\\Youdu\\Messages\\App\\Link"          => "Huangdijia\\Youdu\\Messages\\Link",
            "Huangdijia\\Youdu\\Messages\\App\\Mail"          => "Huangdijia\\Youdu\\Messages\\Mail",
            "Huangdijia\\Youdu\\Messages\\App\\Message"       => "Huangdijia\\Youdu\\Messages\\Message",
            "Huangdijia\\Youdu\\Messages\\App\\Mpnews"        => "Huangdijia\\Youdu\\Messages\\Mpnews",
            "Huangdijia\\Youdu\\Messages\\App\\PopWindow"     => "Huangdijia\\Youdu\\Messages\\PopWindow",
            "Huangdijia\\Youdu\\Messages\\App\\Sms"           => "Huangdijia\\Youdu\\Messages\\Sms",
            "Huangdijia\\Youdu\\Messages\\App\\SysMsg"        => "Huangdijia\\Youdu\\Messages\\SysMsg",
            "Huangdijia\\Youdu\\Messages\\App\\Text"          => "Huangdijia\\Youdu\\Messages\\Text",
            "Huangdijia\\Youdu\\Messages\\App\\Items\\Exlink" => "Huangdijia\\Youdu\\Messages\\Items\\Exlink",
            "Huangdijia\\Youdu\\Messages\\App\\Items\\Item"   => "Huangdijia\\Youdu\\Messages\\Items\\Item",
            "Huangdijia\\Youdu\\Messages\\App\\Items\\Mpnews" => "Huangdijia\\Youdu\\Messages\\Items\\Mpnews",
            "Huangdijia\\Youdu\\Messages\\App\\Items\\SysMsg" => "Huangdijia\\Youdu\\Messages\\Items\\SysMsg",
        ];

        collect($aliases)->each(function ($alias, $original) {
            class_alias($original, $alias, true);
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
