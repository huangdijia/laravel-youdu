<?php

declare(strict_types=1);
/**
 * This file is part of huangdijia/laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/3.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Tests;

use Huangdijia\Youdu\Facades\Youdu;
use Huangdijia\Youdu\YouduServiceProvider;

/**
 * @internal
 * @coversNothing
 */
class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Your code here
    }

    protected function getPackageProviders($app)
    {
        return [
            YouduServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            // 'Youdu' => Youdu::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('youdu.api', env('YOUDU_API'));
        $app['config']->set('youdu.buin', env('YOUDU_BUIN'));
        $app['config']->set('youdu.apps.default.app_id', env('YOUDU_DEFAULT_APP_ID'));
        $app['config']->set('youdu.apps.default.aes_key', env('YOUDU_DEFAULT_AES_KEY'));
    }
}
