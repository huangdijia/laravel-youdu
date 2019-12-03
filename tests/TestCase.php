<?php

namespace Huangdijia\Youdu\Tests;

use Huangdijia\Youdu\Contracts\HttpClient;
use Huangdijia\Youdu\Dept;
use Huangdijia\Youdu\Facades\Youdu;
use Huangdijia\Youdu\Group;
use Huangdijia\Youdu\Manager;
use Huangdijia\Youdu\Media;
use Huangdijia\Youdu\Session;
use Huangdijia\Youdu\User;
use Huangdijia\Youdu\YouduServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
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
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('youdu.api', env('YOUDU_API'));
        $app['config']->set('youdu.buin', env('YOUDU_BUIN'));
        $app['config']->set('youdu.apps.default.app_id', env('YOUDU_DEFAULT_APP_ID'));
        $app['config']->set('youdu.apps.default.aes_key', env('YOUDU_DEFAULT_AES_KEY'));
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Your code here
    }
}
