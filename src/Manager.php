<?php

declare(strict_types=1);
/**
 * This file is part of hyperf/helpers.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
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

    public function __construct(protected array $config)
    {
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
                (int) $this->config['buin'],
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
