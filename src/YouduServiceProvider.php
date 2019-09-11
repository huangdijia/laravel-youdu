<?php

namespace Huangdijia\Youdu;

use Illuminate\Support\ServiceProvider;

class YouduServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/youdu.php' => config_path('youdu.php')]);
        }
    }

    public function register()
    {
        $this->app->singleton(Youdu::class, function () {
            return new Youdu(config('youdu.api'), (int) config('youdu.buin'), config('youdu.app_id', ''), config('youdu.ase_key', ''));
        });

        $this->app->alias(Youdu::class, 'youdu');
    }

    public function provides()
    {
        return [
            Youdu::class,
            'youdu',
        ];
    }
}
