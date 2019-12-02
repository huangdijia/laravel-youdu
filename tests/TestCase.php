<?php

namespace Huangdijia\Youdu\Tests;

use Huangdijia\Youdu\App;
use Huangdijia\Youdu\Dept;
use Huangdijia\Youdu\Facades\Youdu;
use Huangdijia\Youdu\Group;
use Huangdijia\Youdu\Http\Client;
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

    /**
     * Test the configs
     *
     * @return void
     */
    public function testConfigs()
    {
        $this->assertTrue(Youdu::getBuin() == env('YOUDU_BUIN'));
        $this->assertTrue(Youdu::getAppId() == env('YOUDU_DEFAULT_APP_ID'));
        $this->assertTrue(Youdu::getAesKey() == env('YOUDU_DEFAULT_AES_KEY'));

        $this->assertTrue(Youdu::config('api') == env('YOUDU_API'));
        $this->assertTrue(Youdu::config('buin') == env('YOUDU_BUIN'));
    }

    /**
     * Test the containers
     *
     * @return void
     */
    public function testContainers()
    {
        $this->assertInstanceOf(Manager::class, app('youdu.manager'));
        $this->assertInstanceOf(Client::class, app('youdu.http.client'));
    }

    /**
     * Test the objects of app
     *
     * @return void
     */
    public function testAppObjects()
    {
        $this->assertInstanceOf(Dept::class, Youdu::dept());
        $this->assertInstanceOf(Group::class, Youdu::group());
        $this->assertInstanceOf(Media::class, Youdu::media());
        $this->assertInstanceOf(Session::class, Youdu::session());
        $this->assertInstanceOf(User::class, Youdu::user());
    }
}
