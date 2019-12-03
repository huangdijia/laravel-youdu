<?php

namespace Huangdijia\Youdu\Tests;

use Huangdijia\Youdu\Facades\Youdu;

class UserTest extends TestCase
{
    public function testSimpleList()
    {
        $lists = Youdu::user()->simpleList(1);

        $this->assertNotEmpty($lists);
        $this->assertIsArray($lists);
    }

    public function testLists()
    {
        $lists = Youdu::user()->lists(1);

        $this->assertNotEmpty($lists);
        $this->assertIsArray($lists);
    }

    public function testInfo()
    {
        $info = Youdu::user()->get(10400);

        $this->assertNotEmpty($info);
        $this->assertIsArray($info);
        $this->assertNotEmpty($info['userId']);
        $this->assertEquals('10400', $info['userId']);
    }
}
