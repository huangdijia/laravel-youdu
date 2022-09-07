<?php
/**
 * This file is part of laravel-youdu.
 *
 * @link     https://github.com/huangdijia/laravel-youdu
 * @document https://github.com/huangdijia/laravel-youdu/blob/2.x/README.md
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
