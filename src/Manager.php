<?php

namespace Huangdijia\Youdu;

use Illuminate\Support\Arr;

class Manager
{
    protected $apps;
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string|null $name
     */
    public function app(?string $name = null)
    {
        $name = $name ?: Arr::get($this->config, 'default', 'default');

        if (isset($this->apps[$name])) {
            return $this->apps[$name];
        }

        if (!isset($this->config['apps'][$name])) {
            throw new \Exception("config 'youdu.apps.{$name}' is undefined", 1);
        }

        $config = $this->config['apps'][$name];

        return $this->apps[$name] = new Youdu(
            $this->config['api'],
            $this->config['buin'],
            $config['app_id'],
            $config['aes_key']
        );
    }

    /**
     * @return mixed
     */
    public function apps()
    {
        return $this->apps;
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
}
