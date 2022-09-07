<?php
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu;

use Huangdijia\Youdu\Exceptions\Exception;
use Illuminate\Support\Arr;

/**
 * @mixin \Huangdijia\Youdu\App
 */
class Manager
{
    /**
     * @var App[]
     */
    protected $apps = [];

    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->app()->{$method}(...$parameters);
    }

    /**
     * Get config.
     *
     * @param mixed $default
     */
    public function config(?string $key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->config;
        }

        return Arr::get($this->config, $key, $default);
    }

    /**
     * Get an app.
     * @return App
     */
    public function app(?string $name = null)
    {
        $name = $name ?: Arr::get($this->config, 'default', 'default');

        if (! isset($this->apps[$name])) {
            if (! isset($this->config['apps'][$name])) {
                throw new Exception("config 'youdu.apps.{$name}' is undefined", 1);
            }

            $config = $this->config['apps'][$name];

            $this->apps[$name] = new App(
                $this->config['api'],
                $this->config['buin'],
                $config['app_id'],
                $config['aes_key']
            );
        }

        return $this->apps[$name];
    }

    /**
     * Get all app.
     *
     * @return App[]
     */
    public function apps()
    {
        return $this->apps;
    }
}
