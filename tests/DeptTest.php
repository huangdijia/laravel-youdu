<?php

namespace Huangdijia\Youdu\Tests;

use Huangdijia\Youdu\Facades\Youdu;

class DeptTest extends TestCase
{
    public function testLists()
    {
        $depts = Youdu::dept()->lists();

        $this->assertNotEmpty($depts);
        $this->assertIsArray($depts);
    }
}
