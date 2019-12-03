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

class BaseTest extends TestCase
{
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

        $this->assertIsArray(Youdu::config());
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
        $this->assertInstanceOf(HttpClient::class, app('youdu.http.client'));
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
