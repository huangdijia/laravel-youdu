<?php
/**
 * This file is part of Hyperf.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/master/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\Youdu\Tests;

use Huangdijia\Youdu\Facades\Youdu;

/**
 * @internal
 * @coversNothing
 */
class DeptTest extends TestCase
{
    public function testLists()
    {
        $depts = Youdu::dept()->lists();

        $this->assertNotEmpty($depts);
        $this->assertIsArray($depts);
    }
}
